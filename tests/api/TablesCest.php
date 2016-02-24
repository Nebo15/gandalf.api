<?php

class TablesCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function create(ApiTester $I)
    {
        $I->loginAdmin();
        $table = $I->createTable([
            'default_decision' => 'Decline',
            'title' => 'Test title',
            'description' => 'Test description',
            'fields' => [
                [
                    "key" => ' Test INVALID key ',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'integer',
                    "preset" => [
                        'condition' => '$lte',
                        'value' => 10,
                    ],
                    'test' => 'INVALID'
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => ' Test INVALID key ',
                            'condition' => '$eq',
                            'value' => 'okay',
                            'matched' => true,
                        ]
                    ]
                ]
            ]
        ]);

        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->assertTable();
        $I->dontSeeResponseJsonMatchesJsonPath("$.data.fields[*].test");
        $I->seeResponseContainsJson(

        );
        $I->assertEquals('test_invalid_key', $table->fields[0]->key);
        $I->assertEquals('test_invalid_key', $table->rules[0]->conditions[0]->field_key);
    }

    public function all(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertListTable();
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/tables/' . $item->_id);
            $I->assertTable();
        }

        $I->logout();
        $I->sendGET('api/v1/admin/tables');
        $I->seeResponseCodeIs(401);
    }

    public function update(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertListTable();

        $id = $I->getResponseFields()->data[0]->_id;
        $data = $I->getTableData();
        $data['title'] = 'Updated title';
        $data['description'] = 'Updated description';
        $data['fields'] = [
            [
                "key" => 'test_key',
                "title" => 'Test key',
                "source" => "request",
                "type" => 'string',
            ]
        ];
        $data['rules'] = [
            [
                'than' => 'Approve',
                'description' => 'New rule',
                'conditions' => [
                    [
                        'field_key' => 'test_key',
                        'condition' => '$eq',
                        'value' => 'test'
                    ]
                ]
            ]
        ];
        $I->sendPUT('api/v1/admin/tables/' . $id, ['table' => $data]);
        $I->assertTable();
        $I->assertResponseDataFields([
            'title' => $data['title'],
            'description' => $data['description'],
            'fields' => [
                [
                    "key" => 'test_key',
                    "title" => 'Test key',
                    "source" => "request",
                    "type" => 'string',
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'description' => 'New rule',
                    'conditions' => [
                        [
                            'field_key' => 'test_key',
                            'condition' => '$eq',
                            'value' => 'test'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function cloning(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();

        $data = $I->getResponseFields()->data;
        $id = $data->_id;
        $I->sendPOST("api/v1/admin/tables/$id/clone", []);
        $I->assertTable();
        $cloneData = $I->getResponseFields()->data;
        unset($cloneData->_id);
        unset($data->_id);

        $I->assertEquals($data, $cloneData);
    }

    public function delete(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertListTable();

        $response = $I->getResponseFields();
        $id = $response->data[0]->_id;
        $id2 = $response->data[1]->_id;
        $I->sendDELETE('api/v1/admin/tables/' . $id);

        $I->sendGET('api/v1/admin/tables/' . $id);
        $I->seeResponseCodeIs(404);

        $I->sendGET('api/v1/admin/tables/' . $id2);
        $I->assertTable();
    }

    public function decisions(ApiTester $I)
    {
        $I->loginAdmin();
        $table_data = $I->createTable();
        $table_id_no_decisions = $table_data->_id;

        $table_data = $I->createTable();

        $table_id_with_decisions = $table_data->_id;
        $decision_table = $I->checkDecision($table_id_with_decisions);
        $I->assertEquals('Approve', $decision_table->final_decision);

        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_no_decisions);
        $I->seeResponseCodeIs(404);

        $I->sendGET('api/v1/admin/decisions');
        $I->assertTableDecisionsForAdmin('$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }

        # filter by table_id
        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_with_decisions);
        $I->assertTableDecisionsForAdmin('$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }

        $decision_data = $I->checkDecision($table_id_with_decisions, [
            'borrowers_phone_verification' => 'invalid',
            'contact_person_phone_verification' => 'invalid',
            'internal_credit_history' => 'invalid',
            'employment' => false,
            'property' => false,
        ]);
        $I->assertEquals($table_data->default_decision, $decision_data->final_decision);
    }

    public function invalidDecisions(ApiTester $I)
    {
        $I->loginAdmin();
        $table_id = $I->createTable()->_id;

        $I->sendPOST("api/v1/tables/$table_id/check", ['internal_credit_history' => 'okay']);
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType([
            'borrowers_phone_verification' => 'array',
            'contact_person_phone_verification' => 'array',
            'property' => 'array',
            'employment' => 'array',
        ], '$.data');

        $I->sendPOST("api/v1/tables/$table_id/check", [
            'internal_credit_history' => 'okay',
            'borrowers_phone_verification' => 'okay',
            'contact_person_phone_verification' => 'okay',
            'property' => 'okay',
            'employment' => 'okay',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType(['property' => 'array', 'employment' => 'array'], '$.data');
    }
}
