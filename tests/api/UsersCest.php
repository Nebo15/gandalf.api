<?php

class UsersCest
{
    public function _before(ApiTester $I, \Codeception\Scenario $scenario)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createUserSuccess(ApiTester $I)
    {
        $I->createAndLoginClient();
        $faker = $I->getFaker();
        $I->sendPOST(
            'api/v1/users/',
            ['email' => $faker->email, 'password' => $faker->password(), 'username' => $faker->firstName]
        );
        $I->seeResponseCodeIs(201);
    }

    public function editUser(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $user_edited_data = [
            'last_name' => $user->last_name . 'edited',
            'first_name' => $user->first_name . 'edited',
            'username' => $user->username . 'edited',
        ];
        $I->sendPUT('api/v1/users/current', $user_edited_data);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains($user_edited_data['last_name']);
        $I->seeResponseContains($user_edited_data['first_name']);
        $I->seeResponseContains($user_edited_data['username']);
    }

    public function createUserBadData(ApiTester $I)
    {
        $I->createAndLoginClient();
        $faker = $I->getFaker();
        $badData = [
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
            ],
        ];
        /** Normal user for Test Duplicate Username */
        $I->sendPOST(
            'api/v1/users/',
            ['email' => $faker->email, 'password' => $faker->password(), 'username' => 'duplicate']
        );
        $I->seeResponseCodeIs(201);
        foreach ($badData as $key => $data) {
            $normalUserData = [
                'email' => $faker->email,
                'password' => $faker->password(),
                'username' => $faker->firstName,
            ];
            foreach ($data as $item) {
                $normalUserData[$key] = $item;
                $I->sendPOST('api/v1/users/', $normalUserData);
                $I->seeResponseCodeIs(422);
                $I->seeResponseContains('"error":"validation"}');
                $I->seeResponseContains($key);
            }
        }
    }

    public function findUsers(ApiTester $I, $scenario)
    {
        $usersList = [
            $I->createUser(true),
            $I->createUser(true),
            $I->createUser(true),
            $I->createUser(true),
        ];
        $I->createAndLoginUser();
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
        $I->sendPOST('api/v1/oauth/',
            [
                'grant_type' => 'password',
                'username' => $user->email,
                'password' => $user->password,
            ]
        );
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
        $new_password = $I->getFaker()->password() . '1a';
        $I->sendPUT('api/v1/users/password',
            ['token' => $resp->sandbox->reset_password_token->token, 'password' => $new_password]);
        $I->seeResponseCodeIs(200);

        $I->sendPOST('api/v1/oauth/',
            [
                'grant_type' => 'password',
                'username' => $user->email,
                'password' => $old_password,
            ]
        );
        $I->seeResponseCodeIs(401);

        $I->sendPOST('api/v1/oauth/',
            [
                'grant_type' => 'password',
                'username' => $user->email,
                'password' => $new_password,
            ]
        );
        $I->seeResponseCodeIs(200);

    }
}
