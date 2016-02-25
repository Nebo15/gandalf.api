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
                    'title' => '',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => ' Test INVALID key ',
                            'condition' => '$eq',
                            'value' => true,
                            'matched' => true,
                        ]
                    ]
                ]
            ]
        ]);

        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->assertTable();
        $I->assertResponseDataFields([
            'fields' => [
                [
                    "preset" => [
                        'condition' => '$lte',
                        'value' => 10,
                    ],
                ]
            ]
        ]);

        $I->dontSeeResponseJsonMatchesJsonPath("$.data.fields[*].test");
        $I->assertEquals('test_invalid_key', $table->fields[0]->key);
        $I->assertEquals('test_invalid_key', $table->rules[0]->conditions[0]->field_key);

        # assert preset
        $decision = $I->checkDecision($table->_id, ['test_invalid_key' => 30]);
        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->assertResponseDataFields([
            'final_decision' => 'Decline',
            'fields' => [
                [
                    "preset" => [
                        'condition' => '$lte',
                        'value' => 10,
                    ],
                ]
            ],
            'rules' => [
                [
                    'conditions' => [
                        [
                            'field_key' => 'test_invalid_key',
                            'matched' => false
                        ]
                    ]
                ]
            ]
        ]);

        $decision = $I->checkDecision($table->_id, ['test_invalid_key' => 8]);
        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->assertResponseDataFields([
            'final_decision' => 'Approve',
            'fields' => [
                [
                    "preset" => [
                        'condition' => '$lte',
                        'value' => 10,
                    ],
                ]
            ],
            'rules' => [
                [
                    'conditions' => [
                        [
                            'field_key' => 'test_invalid_key',
                            'matched' => true
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function ruleIsset(ApiTester $I)
    {
        $I->loginAdmin();
        $table = $I->createTable([
            'title' => 'Test title',
            'description' => 'Test description',
            'default_decision' => 'Decline',
            'fields' => [
                [
                    "key" => ' IS SET ',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'integer',
                    "preset" => [
                        'condition' => '$lte',
                        'value' => 10,
                    ],
                ],
                [
                    "key" => 'Second ',
                    "title" => 'Second',
                    "source" => "request",
                    "type" => 'string'
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => '',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => ' IS SET ',
                            'condition' => '$is_set',
                            'value' => true,
                        ],
                        [
                            'field_key' => 'Second ',
                            'condition' => '$is_set',
                            'value' => true,
                        ]
                    ]
                ]
            ]
        ]);
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", [' ISSET ' => 8]);
        $I->seeResponseCodeIs(422);

        $I->loginConsumer();
        $decision = $I->checkDecision($table->_id, ['is_set' => 1000, 'second' => 'test']);

        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->seeResponseCodeIs(401);

        $I->loginAdmin();
        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->assertResponseDataFields([
            'final_decision' => 'Approve',
            'rules' => [
                [
                    'conditions' => [
                        [
                            'field_key' => 'is_set',
                            'matched' => true
                        ],
                        [
                            'field_key' => 'second',
                            'matched' => true
                        ]
                    ]
                ]
            ]
        ]);
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

        $I->sendGET('api/v1/admin/decisions');
        $I->assertTableDecisionsForAdmin('$.data[*]');

        $decisions = $I->getResponseFields()->data;
        $I->assertEquals('invalid', $decisions[0]->request->borrowers_phone_verification);
        $I->assertEquals('Positive', $decisions[1]->request->borrowers_phone_verification);

        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }

        $I->loginConsumer();
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);
    }

    public function invalidDecisions(ApiTester $I)
    {
        $I->loginAdmin();
        $table_id = $I->createTable()->_id;

        $I->sendPOST("api/v1/tables/$table_id/decisions", ['internal_credit_history' => 'okay']);
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType([
            'borrowers_phone_verification' => 'array',
            'contact_person_phone_verification' => 'array',
            'property' => 'array',
            'employment' => 'array',
        ], '$.data');

        $I->sendPOST("api/v1/tables/$table_id/decisions", [
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
