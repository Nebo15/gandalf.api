<?php

class ProjectsCest
{
    public function _before(ApiTester $I, \Codeception\Scenario $scenario)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createProject(ApiTester $I)
    {
        $I->createAndLoginUser();
        $faker = $I->getFaker();
        $project = [
            'title' => $faker->streetName,
            'description' => $faker->text('150'),
        ];
        $I->sendPOST('api/v1/projects', $project);
        $project = json_decode($I->grabResponse());
        $I->assertProject('$.data', 201);
        $project_id = $project->data->_id;

        $I->setHeader('X-Application', $project_id);
        $I->sendPOST('api/v1/projects/consumers', ['description' => $faker->text('20'), 'scope' => ['check']]);
        $I->assertProject('$.data', 201);

        $I->sendPOST('api/v1/projects/consumers',
            ['description' => $faker->text('20'), 'scope' => ['check', 'undefined_scope']]);
        $I->seeResponseCodeIs(422);
    }

    public function projectVisibility(ApiTester $I)
    {
        $first_user = $I->createUser(true);
        $second_user = $I->createUser(true);

        $I->loginUser($first_user);
        $project = $I->createProjectAndSetHeader();

        $I->sendGET('api/v1/projects');

        $I->assertContains($project->_id, $I->grabResponse());
        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'read', 'update']]);
        $I->seeResponseCodeIs(201);
        $I->loginUser($second_user);
        $I->sendGET('api/v1/projects');
        $I->assertContains($project->_id, $I->grabResponse());

        $I->loginUser($first_user);
        $I->sendPUT('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'update']]);

        $I->loginUser($second_user);
        $I->sendGET('api/v1/projects');
        $I->assertNotContains($project->_id, $I->grabResponse());
    }

    public function updateProject(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->sendPUT('api/v1/projects', ['description' => 'Edited']);
        $I->assertProject();
        $I->seeResponseContains('"description":"Edited"');
    }

    public function deleteProject(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table = $I->createTable();
        $I->sendDELETE('api/v1/projects');
        $I->seeResponseCodeIs(200);
        $I->sendGET('api/v1/admin/tables' . $table->_id);
        $I->seeResponseCodeIs(404);
    }

    public function testScope(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->loginClient($I->getCurrentClient());
        $second_user = $I->createUser(true);
        $I->loginUser($user);
        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['read', 'update']]);
        $I->loginUser($second_user);
        $I->sendPOST('api/v1/admin/tables', $I->getTableData());
        $I->seeResponseCodeIs(403);
        $I->seeResponseContains('"meta":{"error_message":"Bad Scopes","scopes":["create"],"code":403,"error":"access_denied"}');
    }

    public function duplicateUserToTheProject(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->loginClient($I->getCurrentClient());
        $second_user = $I->createUser(true);
        $I->loginUser($user);
        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['read', 'update']]);
        $I->seeResponseCodeIs(201);
        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['read', 'update']]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContains('duplicate user');

        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $user->_id, 'role' => 'manager', 'scope' => ['read', 'update']]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContains('duplicate user');
    }

    public function getConsumers(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->loginClient($I->getCurrentClient());
        $second_user = $I->createUser(true);
        $I->loginUser($user);
        $I->sendGET('api/v1/projects');
        $I->seeResponseCodeIs(200);
        $I->cantSeeResponseContains("\"consumers\":");
        $I->createConsumer();
        $I->createConsumer();
        $I->createConsumer();
        $I->sendGET('api/v1/projects/consumers');
        $I->assertConsumers();
        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['read', 'update']]);
        $I->loginUser($second_user);
        $I->sendGET('api/v1/projects/consumers');
        $I->seeResponseCodeIs(403);
        $I->loginUser($user);
        $I->sendPUT('api/v1/projects/users/',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['read', 'update', 'get_consumers']]);
        $I->loginUser($second_user);
        $I->sendGET('api/v1/projects/consumers');
        $I->assertConsumers();
    }
}
