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
        $table2 = $I->createTable($I->getShortTableDataMatchingTypeAll());

        $invalid_data = [
            ['probability' => 'random'],
            ['tables' => ['_id' => $table->_id], 'probability' => 'random'],
            ['tables' => [['_id' => $table->_id]], 'probability' => 'invalid'],
            ['tables' => [['invalid' => 'test']], 'probability' => 'random'],
            ['tables' => [['_id' => $table2->_id]], 'probability' => 'random'],
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

        $group1Tables = $I->stdToArray($group1->tables);
        $group1Tables[2] = ['_id' => $table->_id];
        $I->sendPUT($this->api_prefix . "/$group1->_id", [
            'tables' => $group1Tables,
            'invalid_field' => 'test'
        ]);

        $I->assertResponseDataFields(['tables' => $group1Tables]);
        $I->dontSeeResponseJsonMatchesJsonPath("$.data.invalid_field");

        $group2 = $I->createGroup();
        $group2Tables = $I->stdToArray($group2->tables);
        $group2Tables[2] = ['_id' => $table->_id];
        $I->sendPUT($this->api_prefix . "/$group2->_id", ['tables' => $group2Tables]);
        $I->seeResponseCodeIs(200);

        $tableData = $I->getTableData();

        # remove ids from array, because different Group Tables has different _id for Field
        $fields = $I->removeIdsFromArray($tableData['fields']);
        $fields[4] = [
            'key' => 'updated_key',
            'title' => 'new title',
            'source' => 'request',
            'type' => 'boolean',
            'preset' => null,
        ];
        $fields[] = [
            'key' => 'new_key',
            'title' => 'new title',
            'source' => 'request',
            'type' => 'string',
            'preset' => null,
        ];
        $rules = $tableData['rules'];
        $newRules = [];
        foreach ($rules as $rule) {
            $rule['conditions'][4]['field_key'] = 'updated_key';
            $rule['conditions'][5] = [
                'field_key' => 'new_key',
                'condition' => '$eq',
                'value' => 'me',
            ];
            $newRules[] = $rule;
        }
        $tableData['rules'] = $newRules;
        $tableData['fields'] = $fields;
        $I->sendPUT('api/v1/admin/tables/' . $table->_id, ['table' => $tableData]);
        $I->seeResponseCodeIs(200);

        $tableIds = array_unique(array_merge(array_column($group1Tables, '_id'), array_column($group2Tables, '_id')));
        foreach ($tableIds as $table_id) {
            $I->sendGET("api/v1/admin/tables/$table_id");
            $data = $I->getResponseFields()->data;
            $I->assertEquals($fields, $I->removeIdsFromArray($I->stdToArray($data->fields)));
            foreach ($data->rules as $rule) {
                $I->assertEquals(6, count($rule->conditions), "Wrong amount of Rule.Conditions after Table update");
                $conditionsActual = ['new_key', 'updated_key'];
                $conditionUpdated = [];
                foreach ($rule->conditions as $condition) {
                    if (in_array($condition->field_key, $conditionsActual)) {
                        $conditionUpdated[] = $condition->field_key;
                    }
                }
                sort($conditionUpdated);
                $I->assertEquals($conditionsActual, $conditionUpdated, 'Some of the conditions does not updated');
            }
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
