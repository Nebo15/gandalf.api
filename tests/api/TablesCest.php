<?php

class TablesCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createOk(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'default_decision' => 'Decline',
            'default_title' => 'Title 100',
            'default_description' => 'Description 220',
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => ' Test INVALID key ',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric',
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
                    'title' => 'Valid rule title',
                    'description' => 'Valid rule description',
                    'conditions' => [
                        [
                            'field_key' => ' Test INVALID key ',
                            'condition' => '$eq',
                            'value' => true,
                            'matched' => true,
                        ]
                    ]
                ],
                [
                    'than' => 'Decline',
                    'title' => 'Second title',
                    'description' => 'Second description',
                    'conditions' => [
                        [
                            'field_key' => ' Test INVALID key ',
                            'condition' => '$eq',
                            'value' => false,
                        ]
                    ]
                ]
            ]
        ]);

        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->assertTable();
        $I->assertResponseDataFields([
            'default_title' => 'Title 100',
            'default_description' => 'Description 220',
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
            'title' => 'Valid rule title',
            'description' => 'Valid rule description',
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

    public function createInvalid(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $I->sendPOST('api/v1/admin/tables', [
            'table' => [
                'default_title' => '',
                'default_description' => str_repeat('1', 513),
                'default_decision' => 'Decline',
                'fields' => [
                    [
                        "key" => '1',
                        "title" => 'Test',
                        "source" => "request",
                        "type" => 'numeric',
                    ],
                    [
                        "key" => '2',
                        "title" => 'Test 2',
                        "source" => "request",
                        "type" => 'numeric',
                    ],
                    [
                        "key" => '3',
                        "title" => 'Test 3',
                        "source" => "request",
                        "type" => 'numeric',
                    ],
                    [
                        "key" => 'webhook',
                        "title" => 'Test 3',
                        "source" => "request",
                        "type" => 'numeric',
                    ],
                ],
                'rules' => [
                    [
                        'than' => 'Approve',
                        'title' => 'Valid rule title',
                        'description' => 'Valid rule description',
                        'conditions' => [
                            [
                                'field_key' => '3',
                                'condition' => '$eq',
                                'value' => true,
                            ],
                            [
                                'field_key' => '1',
                                'condition' => '$eq',
                                'value' => true,
                            ],
                            [
                                'field_key' => '2',
                                'condition' => '$eq',
                                'value' => true,
                            ],

                        ]
                    ],
                    [
                        'than' => 'Decline',
                        'title' => 'Second title',
                        'description' => 'Second description',
                        'conditions' => [
                            [
                                'field_key' => '3',
                                'condition' => '$eq',
                                'value' => true,
                            ],
                            [
                                'field_key' => '2',
                                'condition' => '$eq',
                                'value' => false,
                            ],
                        ]
                    ]
                ]
            ]
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('table.default_title');
        $I->seeResponseContains('table.default_description');
        $I->seeResponseContains('table.rules.1.conditions');
        $I->seeResponseContains('table.matching_type');
        $I->seeResponseContains('table.fields.3.key');

        $I->sendPOST('api/v1/admin/tables', [
            'table' => [
                'default_decision' => 'Decline',
                'fields' => [
                    [
                        "key" => '1',
                        "title" => 'Test',
                        "source" => "request",
                        "type" => 'numeric',
                    ]
                ],
                'rules' => [
                    [
                        'than' => 'Approve',
                        'title' => 'Valid rule title',
                        'description' => 'Valid rule description',
                        'conditions' => [
                            [
                                'field_key' => '1',
                                'condition' => '$lte',
                                'value' => 'invalid',
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('table.rules.0.conditions.0.value');
    }

    public function ruleIsset(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'title' => 'Test title',
            'description' => 'Test description',
            'default_decision' => 'Decline',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => ' IS SET ',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric',
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

        # invalid request type
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", ['is_set' => 'invalid_type', 'second' => 'test']);
        $I->seeResponseCodeIs(422);

        # invalid request type
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", ['is_set' => 200, 'second' => false]);
        $I->seeResponseCodeIs(422);

        $decision = $I->checkDecision($table->_id, ['is_set' => 1000, 'second' => 'test']);

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

    public function ruleIn(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'title' => 'Test title',
            'description' => 'Test description',
            'default_decision' => 'Decline',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => 'test',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'string'
                ],
                [
                    "key" => 'test',
                    "title" => 'Preset wow',
                    "source" => "request",
                    "type" => 'string',
                    "preset" => [
                        'condition' => '$nin',
                        'value' => "1, 3, 'another,comma'",
                    ],
                ],
                [
                    "key" => 'test',
                    "title" => 'Preset third',
                    "source" => "request",
                    "type" => 'string',
                    "preset" => [
                        'condition' => '$in',
                        'value' => "1, 3, 'third,comma'",
                    ],
                ],
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => '',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => 'test',
                            'condition' => '$in',
                            'value' => "1, 3, 'wow,comma'",
                        ],
                        [
                            'field_key' => 'test',
                            'condition' => '$eq',
                            'value' => true,
                        ],
                        [
                            'field_key' => 'test',
                            'condition' => '$eq',
                            'value' => true,
                        ],
                    ]
                ]
            ]
        ]);
        $data = [
            'wow,comma' => [true, true, false],
            'another,comma' => [false, false, false],
            'third,comma' => [false, true, true],
        ];
        foreach ($data as $value => $results) {
            $id = $I->checkDecision($table->_id, ['test' => $value])->_id;
            $I->sendGET('api/v1/admin/decisions/' . $id);
            $I->assertResponseDataFields(
                [
                    'rules' => [
                        'conditions' => [
                            [
                                'field_key' => 'test',
                                'value' => "1, 3, 'wow,comma'",
                                'matched' => $results[0]
                            ],
                            [
                                'field_key' => 'test',
                                'condition' => '$eq',
                                'matched' => $results[1],
                            ],
                            [
                                'field_key' => 'test',
                                'condition' => '$eq',
                                'matched' => $results[2],
                            ],
                        ]
                    ]
                ]
            );
        }
    }

    public function ruleEqual(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $table_data = [
            'title' => 'Test',
            'description' => 'Test',
            'default_decision' => 'Decline',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => 'boolean',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'boolean'
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => '',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => 'boolean',
                            'condition' => '$eq',
                            'value' => true,
                        ]
                    ]
                ]
            ]
        ];

        # boolean
        $table = $I->createTable($table_data);
        foreach ([true, '1', 1] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Approve']);
        }
        foreach ([false, '0', 0] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach (['invalid', 'true', "true", 100, null] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }

        # string
        $table_data['fields'][0]['type'] = 'string';
        $table_data['rules'][0]['conditions'][0]['value'] = 'string';
        $table = $I->createTable($table_data);
        foreach ([true, null, 1, ''] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach (['invalid', '123321', 'true', "true"] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        $I->checkDecision($table->_id, ['boolean' => 'string']);
        $I->assertResponseDataFields(['final_decision' => 'Approve']);

        # numeric
        $table_data['fields'][0]['type'] = 'numeric';
        $table_data['rules'][0]['conditions'][0]['value'] = 100.15;
        $table = $I->createTable($table_data);
        foreach ([true, null, 'invalid', '100.15i'] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach ([100, "100"] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach ([100.15, "100.15"] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Approve']);
        }
    }

    public function ruleNotEqual(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'title' => 'Test',
            'description' => 'Test',
            'default_decision' => 'Decline',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => 'boolean',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric'
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => '',
                    'description' => '',
                    'conditions' => [
                        [
                            'field_key' => 'boolean',
                            'condition' => '$ne',
                            'value' => 100,
                        ]
                    ]
                ]
            ]
        ]);
        foreach ([true, null, 'invalid', '100.15i'] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach ([100, "100"] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach ([100.15, "100.15"] as $value) {
            $I->checkDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Approve']);
        }
    }

    public function all(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
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
        $I->createProjectAndSetHeader();
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

    public function copy(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
        $I->createTable();

        $data = $I->getResponseFields()->data;
        $id = $data->_id;
        $I->sendPOST("api/v1/admin/tables/$id/copy", []);
        $I->assertTable();
        $cloneData = $I->getResponseFields()->data;
        unset($cloneData->_id);
        unset($data->_id);

        $I->assertEquals($data, $cloneData);
    }

    public function delete(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
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

    public function decisionsFirst(ApiTester $I)
    {
        $I->createProjectAndSetHeader();
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
        $I->assertTableDecisionsForAdmin('first', '$.data[*]');
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
        $I->assertTableDecisionsForAdmin('first', '$.data[*]');

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
        $I->createProjectAndSetHeader();
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
