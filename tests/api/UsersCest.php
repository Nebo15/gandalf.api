<?php

class UsersCest
{
    public function _before(ApiTester $I, \Codeception\Scenario $scenario)
    {
        $I->dropDatabase();
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createOk(ApiTester $I)
    {
        $I->createAndLoginClient();
        $faker = $I->getFaker();
        $I->sendPOST(
            'api/v1/users/', [
                'email' => $faker->email,
                'password' => $I->getPassword(),
                'username' => $username = $faker->firstName,
                'last_name' => "O'Really",
            ]
        );
        $I->seeResponseCodeIs(201);

        $I->sendGET('api/v1/users/username?username=' . $username);
        $I->seeResponseCodeIs(422);
        $I->sendGET('api/v1/users/username?username=' . $username . '-unique');
        $I->seeResponseCodeIs(200);

        $names = [
            'username' => [
                'JL',
                'some.username',
                'some-username',
                'some_username',
                'some.username12345',
            ],
        ];
        foreach ($names as $key => $data) {
            $normalUserData = [
                'password' => $I->getPassword(),
                'username' => $faker->firstName,
            ];
            foreach ($data as $item) {
                $normalUserData['email'] = $faker->email;
                $normalUserData[$key] = $item;
                $I->sendPOST('api/v1/users/', $normalUserData);
                $I->seeResponseCodeIs(201);
            }
        }
    }

    public function updateOk(ApiTester $I)
    {
        $I->createAndLoginClient();
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $user_edited_data = [
            'last_name' => 'LastName',
            'first_name' => $user->first_name . 'edited',
            'username' => $user->username . 'edited',
        ];
        $I->sendPUT('api/v1/users/current', $user_edited_data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains($user_edited_data['last_name']);
        $I->seeResponseContains($user_edited_data['first_name']);
        $I->seeResponseContains($user_edited_data['username']);

        $I->sendGET('api/v1/users/current');
        $I->assertCurrentUser();

        # change email

        $I->sendPUT('api/v1/users/current', ['email' => 'new@email.com']);
        $I->assertResponseDataFields(['email' => $user->email, 'temporary_email' => 'new@email.com']);

        $I->loginClient($I->getCurrentClient());
        $I->sendPOST('api/v1/users/verify/email', ['token' => $I->getResponseFields()->sandbox->token_email->token]);
        $I->seeResponseCodeIs(200);
        $I->assertResponseDataFields(['temporary_email' => null]);

        $I->loginUser($user);
        $I->sendGET('api/v1/users/current');
        $I->assertResponseDataFields(['email' => 'new@email.com']);

        # change password

        $I->sendPUT('api/v1/users/current', ['password' => 'asdQWE123', 'current_password' => $user->password]);
        $I->seeResponseCodeIs(200);

        $I->loginClient($I->getCurrentClient());
        $I->sendPOST('api/v1/oauth/', [
            'grant_type' => 'password',
            'username' => $user->username . 'edited',
            'password' => 'asdQWE123',
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function createUpdateInvalid(ApiTester $I)
    {
        $I->createAndLoginClient();
        $faker = $I->getFaker();

        /** Normal user for Test Duplicate Username */
        $I->sendPOST(
            'api/v1/users/',
            ['email' => $faker->email, 'password' => $I->getPassword(), 'username' => 'duplicate', 'active' => true]
        );
        $I->assertResponseDataFields(['active' => false], 201);

        $invalidData = [
            'email' => [
                '',
                null,
                'test',
                '<script></script>',
                '"alert12"',
                "test@i,ua",
            ],
            'username' => [
                '',
                null,
                '1',
                str_repeat('2', 33),
                'duplicate',
            ],
            'password' => [
                '',
                null,
                '1fa',
                'withoutdigit',
            ],
        ];
        foreach ($invalidData as $key => $data) {
            $normalUserData = [
                'email' => $faker->email,
                'password' => $I->getPassword(),
                'username' => $faker->firstName,
            ];
            foreach ($data as $item) {
                $normalUserData[$key] = $item;
                $I->sendPOST('api/v1/users/', $normalUserData);
                $I->seeResponseCodeIs(422);
                $I->seeResponseContains('"error":"validation"');
                $I->seeResponseContains($key);
            }
        }

        $I->createAndLoginUser(true);
        foreach ($invalidData as $key => $data) {
            foreach ($data as $item) {
                $I->sendPUT('api/v1/users/current', [$key => $item]);
                $I->seeResponseCodeIs(422);
                $I->seeResponseContains('"error":"validation"');
                $I->seeResponseContains($key);
            }
        }
        # change password
        $I->sendPUT('api/v1/users/current', ['password' => $I->getPassword()]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('current_password');
    }

    public function find(ApiTester $I, $scenario)
    {
        $usersList = [
            $I->createUser(true),
            $I->createUser(true),
            $I->createUser(true),
            $I->createUser(true),
        ];
        $user = $I->createAndLoginUser();
        $I->sendGET('api/v1/users');
        $I->sendGET('api/v1/users?name=' . substr($usersList[0]->username, 0, 3));
        $foundUsers = json_encode($I->getResponseFields()->data);
        $I->assertContains($usersList[0]->username, $foundUsers);
        $I->assertContains($usersList[0]->_id, $foundUsers);
        list($email) = explode('@', $usersList[1]->temporary_email);

        $I->sendGET('api/v1/users?name=' . $email . '@');
        $foundUsers = json_encode($I->getResponseFields()->data);
        $I->assertContains($usersList[1]->username, $foundUsers);
        $I->assertContains($usersList[1]->_id, $foundUsers);

        $I->sendGET('api/v1/users?name=' . substr($user->username, 0, 3));
        $foundUsers = json_encode($I->getResponseFields()->data);
        $I->assertNotContains($user->username, $foundUsers);
        $I->assertNotContains($user->_id, $foundUsers);

        $I->loginUser($usersList[0]);

        $I->sendGET('api/v1/users?name=' . substr($user->username, 0, 3));
        $foundUsers = json_encode($I->getResponseFields()->data);
        $I->assertContains($user->username, $foundUsers);
        $I->assertContains($user->_id, $foundUsers);
    }

    public function checkAuthorization(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProject();
        $I->sendGET('api/v1/projects');
        $I->seeResponseCodeIs(200);
        $projects_data = $I->grabResponse();
        $I->logout();
        $I->sendGET('api/v1/projects');
        $I->seeResponseCodeIs(401);

        $I->loginClient($I->getCurrentClient());
        $I->sendPOST('api/v1/oauth/', [
            'grant_type' => 'password',
            'username' => $user->email,
            'password' => $user->password,
        ]);
        $I->seeResponseCodeIs(200);

        $token = json_decode($I->grabResponse());

        $I->logout();

        $I->setHeader('Authorization', 'Bearer ' . $token->access_token);

        $I->sendGET('api/v1/projects');
        $I->seeResponseCodeIs(200);
        $projects_data2 = $I->grabResponse();

        $I->assertEquals($projects_data, $projects_data2);
    }

    public function changePassword(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $old_password = $user->password;
        $I->createProject();
        $I->logout();
        $I->loginClient($I->getCurrentClient());
        $I->sendPOST('api/v1/users/password/reset', ['email' => $user->email]);
        $resp = json_decode($I->grabResponse());

        $new_password = $I->getPassword();
        $I->sendPUT('api/v1/users/password/reset', [
            'token' => $resp->sandbox->reset_password_token->token,
            'password' => $new_password
        ]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('api/v1/oauth/', [
            'grant_type' => 'password',
            'username' => $user->email,
            'password' => $old_password,
        ]);
        $I->seeResponseCodeIs(401);

        $I->sendPOST('api/v1/oauth/', [
            'grant_type' => 'password',
            'username' => $user->email,
            'password' => $new_password,
        ]);
        $I->seeResponseCodeIs(200);
    }

    public function passwordNotCorrect(ApiTester $I)
    {
        $faker = $I->getFaker();
        $I->createAndLoginClient();
        $invalidPass = [
            '123',
            '1Aa34',
            'JustAlpha',
            '#1(*&^(*&^',
            'LongerThan32SymbolsMuchLongerAnd!',
        ];
        foreach ($invalidPass as $pass) {
            $I->sendPOST(
                'api/v1/users/',
                ['email' => $faker->email, 'password' => $pass, 'username' => $faker->firstName]
            );
            $I->seeResponseCodeIs(422, "Password $pass should be detected as Invalid");
        }
    }

    public function invitation(ApiTester $I)
    {
        $faker = $I->getFaker();
        $I->createAndLoginUser();
        $first_project = $I->createProjectAndSetHeader();
        $second_user_email = $faker->email;
        $I->sendPOST('api/v1/invite',
            ['email' => $second_user_email, 'role' => 'manager', 'scope' => ['tables_create', 'tables_view']]);
        $I->seeResponseCodeIs(200);
        $second_project = $I->createProject(true);
        $I->setHeader('X-Application', $second_project->_id);
        $I->sendPOST('api/v1/invite',
            ['email' => $second_user_email, 'role' => 'manager', 'scope' => ['tables_create', 'tables_view']]);
        $I->seeResponseCodeIs(200);

        $I->logout();
        $I->loginClient($I->getCurrentClient());

        $I->loginUser($I->createUser(true, $second_user_email));

        $I->sendGET('api/v1/projects');

        $I->seeResponseCodeIs(200);
        $I->assertContains($first_project->_id, $I->grabResponse());
        $I->assertContains($second_project->_id, $I->grabResponse());
    }

    public function accessForCheck(ApiTester $I)
    {
        $first_user = $I->createUser(true, '', false);
        $second_user = $I->createUser(true);

        $I->loginUser($first_user);
        $I->createProjectAndSetHeader();
        $consumer = $I->createConsumer();

        $table = $I->createTable();
        $table_id = $table->_id;
        $data = [
            'borrowers_phone_verification' => 'Positive',
            'contact_person_phone_verification' => 'Positive',
            'internal_credit_history' => 'Positive',
            'employment' => true,
            'property' => true,
            'matching_rules_type' => 'decision',
        ];
        $I->sendPOST("api/v1/tables/$table_id/decisions", $data);
        $I->canSeeResponseCodeIs(403);
        $I->seeResponseContains("Project owner is not activated, try again later");

        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['tables_view', 'decisions_make']]);

        $I->loginUser($second_user);
        $I->sendPOST("api/v1/tables/$table_id/decisions", $data);
        $I->canSeeResponseCodeIs(403);
        $I->seeResponseContains("Project owner is not activated, try again later");

        $I->loginConsumer($consumer);
        $I->sendPOST("api/v1/tables/$table_id/decisions", $data);
        $I->canSeeResponseCodeIs(403);
        $I->seeResponseContains("Project owner is not activated, try again later");

        $I->logout();
        $I->loginClient($I->getCurrentClient());
        $I->sendPOST('api/v1/users/verify/email', ['token' => $first_user->sandbox->token_email->token]);
        $I->seeResponseCodeIs(200);


        $I->loginUser($first_user);
        $I->makeDecision($table_id);

        $I->loginConsumer($consumer);
        $I->makeDecision($table_id);

        $I->loginUser($second_user);
        $I->makeDecision($table_id);
    }

    public function resendVerifyEmailToken(ApiTester $I)
    {
        $user = $I->createUser(true, '', false);
        $I->sendPOST('api/v1/users/verify/email/resend', ['email' => $user->email]);
        $I->seeResponseCodeIs(200);
        $I->sendPOST('api/v1/users/verify/email/resend', ['email' => 'wrong@email.com']);
        $I->seeResponseCodeIs(404);
    }

    public function deleteAdminFromTheProject(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->loginClient($I->getCurrentClient());
        $second_user = $I->createUser(true);
        $third_user = $I->createUser(true);
        $I->loginUser($user);

        $I->sendPOST('api/v1/projects/users',
            [
                'user_id' => $second_user->_id,
                'role' => 'admin',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(422);

        $I->sendPOST('api/v1/projects/users',
            [
                'user_id' => $second_user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(201);

        $I->sendPOST('api/v1/projects/users',
            [
                'user_id' => $third_user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(201);

        $I->loginUser($second_user);

        $I->sendDELETE('api/v1/projects/users', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(422);

        $I->sendDELETE('api/v1/projects/users', ['user_id' => $user->_id]);
        $I->seeResponseCodeIs(403);

        $I->sendDELETE('api/v1/projects/users', ['user_id' => $third_user->_id]);
        $I->seeResponseCodeIs(200);

        $I->loginUser($user);
        $I->sendDELETE('api/v1/projects/users', ['user_id' => $user->_id]);
        $I->seeResponseCodeIs(422);

        $I->sendPOST('api/v1/projects/users/admin', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(200);

        $I->loginUser($second_user);
        $I->sendDELETE('api/v1/projects/users', ['user_id' => $user->_id]);
        $I->seeResponseCodeIs(200);
    }

    public function canNotEditItself(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->sendPUT('api/v1/projects/users',
            [
                'user_id' => $user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(403);
        $I->loginClient($I->getCurrentClient());
        $second_user = $I->createUser(true);
        $I->loginUser($user);
        $I->sendPOST('api/v1/projects/users',
            [
                'user_id' => $second_user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(201);
        $I->loginUser($second_user);
        $I->sendPUT('api/v1/projects/users',
            [
                'user_id' => $user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(403);
        $I->loginUser($user);
        $I->sendPOST('api/v1/projects/users/admin', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(200);
        $I->loginUser($second_user);
        $I->sendPUT('api/v1/projects/users',
            [
                'user_id' => $user->_id,
                'role' => 'manager',
                'scope' => ['tables_view', 'tables_update', 'users_manage'],
            ]);
        $I->seeResponseCodeIs(200);
    }

    public function removeTokens(ApiTester $I)
    {
        $user = $I->createUser();
        $I->getMongo();
        $tokens = [
            'reset_password' => [
                "token" => '$2y$10$iiPJClTgDWOgP0SR1ZgwLeMO4qNZkGFXHRjRpkyl.xC2K6OLPxExK',
                "expired" => time() + 30,
            ],
            'verify_email' => [
                "token" => '$3y$10$iiPJClTgDWOgP0SR1ZgwLeMO4qNZkGFXHRjRpkyl.xC1K6OLPxExK',
                "expired" => time() - 1,
            ],
        ];
        $filter = ['_id' => new MongoDB\BSON\ObjectID($user->_id)];
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update($filter, ['$set' => ['tokens' => $tokens, 'refreshTokens.0.expires' => time() - 1]]);
        $I->getMongo()->executeBulkWrite('gandalf_test.users', $bulk);

        exec('php artisan tokens:delete');

        $query = new MongoDB\Driver\Query($filter);
        $rows = $I->getMongo()->executeQuery('gandalf_test.users', $query);
        $user = $rows->toArray()[0];

        $I->assertFalse(property_exists($user->tokens, 'verify_email'), 'Expired verify_email token don\'t deleted');
        $I->assertEquals(0, count($user->refreshTokens), 'Expired refresh token do not deleted');
    }
}
