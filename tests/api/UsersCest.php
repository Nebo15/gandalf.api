<?php

class UsersCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createUserSuccess(ApiTester $I)
    {
        $I->createAndLoginClient();
        $faker = $I->getFaker();
        $I->sendPOST('api/v1/user/',
            ['email' => $faker->email, 'password' => $faker->password(), 'username' => $faker->firstName]);
        $I->seeResponseCodeIs(201);
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
        $I->sendPOST('api/v1/user/',
            ['email' => $faker->email, 'password' => $faker->password(), 'username' => 'duplicate']);
        $I->seeResponseCodeIs(201);
        foreach ($badData as $key => $data) {
            $normalUserData = [
                'email' => $faker->email,
                'password' => $faker->password(),
                'username' => $faker->firstName,
            ];
            foreach ($data as $item) {
                $normalUserData[$key] = $item;
                $I->sendPOST('api/v1/user/', $normalUserData);
                $I->seeResponseCodeIs(422);
                $I->seeResponseContains('"error":"validation"}');
                $I->seeResponseContains($key);
            }
        }
    }

    public function createProject(ApiTester $I)
    {
        $I->createAndLoginUser();
        $faker = $I->getFaker();
        $project = [
            'title' => $faker->streetName,
            'description' => $faker->text('150')
        ];
        $I->sendPOST('api/v1/projects', $project);
        $project = json_decode($I->grabResponse());
        $I->assertProject('$.data', 201);
        $project_id = $project->data->_id;

        $I->setHeader('X-Application', $project_id);
        $I->sendPOST('api/v1/projects/consumer', ['description' => $faker->text('20'), 'scope' => ['check']]);
        $I->assertProject('$.data', 201);
    }

}
