<?php

class GroupsCest
{
    private $api_prefix = 'api/v1/admin/groups';

    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createOk(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createGroup();

        $I->sendGET($this->api_prefix);
        $I->assertListGroup();

        $group = $I->getResponseFields()->data[0];
        $I->sendGET($this->api_prefix . "/$group->_id");
        $I->assertGroup();

        $tables_ids = [];
        foreach ($group->tables as $table) {
            $I->sendGET("api/v1/admin/tables/$table->_id");
            $I->assertTable();
            $tables_ids[] = $table->_id;
        }
        # check group
        for ($i = 0; $i < 3; $i++) {
            $I->checkDecision($group->_id, [], 'first', 'groups');
            $I->assertTrue(in_array($I->getResponseFields()->data->table->_id, $tables_ids));
        }
    }

    public function createInvalid(ApiTester $I)
    {
        $I->sendPOST($this->api_prefix, []);
        $I->seeResponseCodeIs(401);

        $I->loginAdmin();

        $table = $I->createTable();

        $invalid_data = [
            ['probability' => 'random'],
            ['tables' => ['_id' => $table->_id], 'probability' => 'random'],
            ['tables' => [['_id' => $table->_id]], 'probability' => 'invalid'],
            ['tables' => [['invalid' => 'test']], 'probability' => 'random'],
        ];
        foreach ($invalid_data as $data) {
            $I->sendPOST($this->api_prefix, $data);
            $I->seeResponseCodeIs(422);
        }

        $I->sendPOST($this->api_prefix, ['tables' => [['_id' => 'test']]]);
        $I->seeResponseCodeIs(404);
    }

    public function updateOk(ApiTester $I)
    {
        $I->loginAdmin();
        $group1 = $I->createGroup();

        $table = $I->createTable();

        $I->sendPUT($this->api_prefix . "/$group1->_id", [
            'tables' => [['_id' => $table->_id]],
            'invalid_field' => 'test'
        ]);

        $I->assertResponseDataFields([
            'tables' => [['_id' => $table->_id]]
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath("$.data.invalid_field");

        $group2 = $I->createGroup();
        $I->sendPUT($this->api_prefix . "/$group2->_id", [
            'tables' => [['_id' => $table->_id]],
        ]);

        $fields = $table->fields;
        $fields[0]->key = 'updated_key';
        unset($fields[2]);
        $fields[] = [
            'key' => 'new_key',
            'title' => 'new title',
            'source' => 'new source',
            'type' => 'new type',
            'preset' => null,
        ];
        $I->sendPUT('api/v1/admin/tables/' . $table->_id, ['table' => (array)$table]);
        $table_ids = array_merge(array_column($group1->tables, '_id'), array_column($group2->tables, '_id'));
        foreach ($table_ids as $table_id) {
            $I->sendGET("api/v1/admin/tables/$table_id");
            $I->assertEquals($table->fields, $I->getResponseFields()->data->fields);
        }
    }

    public function updateInvalid(ApiTester $I)
    {
        $I->sendPUT($this->api_prefix, []);
        $I->seeResponseCodeIs(405);

        $I->loginAdmin();
        $id = $I->createGroup()->_id;

        $I->logout();
        $I->sendPUT($this->api_prefix . "/$id", []);
        $I->seeResponseCodeIs(401);

        $I->loginAdmin();

        $notFoundData = [
            ['tables' => [['_id' => strval(new MongoId)]]],
            ['tables' => [['_id' => 'test']]],
        ];
        foreach ($notFoundData as $data) {
            $I->sendPUT($this->api_prefix . "/$id", $data);
            $I->seeResponseCodeIs(404);
        }

        $invalid_data = [
            ['probability' => 'invalid'],
            ['tables' => 'invalid type'],
            ['tables' => [['percent' => 'invalid']]],
        ];
        foreach ($invalid_data as $data) {
            $I->sendPUT($this->api_prefix . "/$id", $data);
            $I->seeResponseCodeIs(422);
        }
    }

    public function copy(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createGroup();

        $data = $I->getResponseFields()->data;
        $id = $data->_id;
        $I->sendPOST($this->api_prefix . "/$id/copy", []);
        $I->assertGroup();
        $cloneData = $I->getResponseFields()->data;
        unset($cloneData->_id);
        unset($data->_id);

        $I->assertEquals($data, $cloneData);
    }

    public function delete(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createGroup();
        $I->createGroup();

        $I->sendGET($this->api_prefix);
        $I->assertListGroup();

        $response = $I->getResponseFields();
        $id = $response->data[0]->_id;
        $id2 = $response->data[1]->_id;
        $I->sendDELETE('api/v1/admin/groups/' . $id);

        $I->sendGET('api/v1/admin/groups/' . $id);
        $I->seeResponseCodeIs(404);

        $I->sendGET('api/v1/admin/groups/' . $id2);
        $I->assertGroup();
    }
}
