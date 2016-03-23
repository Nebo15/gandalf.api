<?php

class TablesCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function createOk(ApiTester $I)
    {
        $I->createAndLoginUser();
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
                    'test' => 'INVALID',
                ],
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
                        ],
                    ],
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
                        ],
                    ],
                ],
            ],
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
                ],
            ],
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
                ],
            ],
            'rules' => [
                [
                    'conditions' => [
                        [
                            'field_key' => 'test_invalid_key',
                            'matched' => false,
                        ],
                    ],
                ],
            ],
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
                ],
            ],
            'rules' => [
                [
                    'conditions' => [
                        [
                            'field_key' => 'test_invalid_key',
                            'matched' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function checkApplicationableAccess(ApiTester $I)
    {
        $first_user = $I->createUser(true);  /** Main user, Project admin */
        $second_user = $I->createUser(true); /** Test user, Project admin */

        $I->loginUser($first_user);
        $I->createProjectAndSetHeader();
        $table = $I->createTable();
        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->seeResponseCodeIs(200);
        $I->loginUser($second_user);
        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->seeResponseCodeIs(403);

        $I->sendPOST('api/v1/projects/users', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(403);
        $I->loginUser($first_user);

        $I->sendPOST('api/v1/projects/users', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('The role field is required');
        $I->seeResponseContains('The scope field is required');

        $I->sendPOST('api/v1/projects/users', ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'read', 'uncreated_scope']]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('The selected scope is invalid.');

        $I->sendPOST('api/v1/projects/users', ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'read', 'update']]);
        $I->seeResponseCodeIs(201);
        $I->loginUser($second_user);
        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->seeResponseCodeIs(200);

        $I->loginUser($first_user);
        $I->sendDELETE('api/v1/projects/users', ['user_id' => $second_user->_id]);
        $I->seeResponseCodeIs(200);

        $I->loginUser($second_user);
        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->seeResponseCodeIs(403);
    }

    public function createInvalid(ApiTester $I)
    {
        $I->createAndLoginUser();
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

                        ],
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
                        ],
                    ],
                ],
            ],
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('table.default_title');
        $I->seeResponseContains('table.default_description');
        $I->seeResponseContains('table.rules.1.conditions');
        $I->seeResponseContains('table.matching_type');

        $I->sendPOST('api/v1/admin/tables', [
            'table' => [
                'default_decision' => 'Decline',
                'fields' => [
                    [
                        "key" => '1',
                        "title" => 'Test',
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
                                'field_key' => '1',
                                'condition' => '$lte',
                                'value' => 'invalid',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('table.rules.0.conditions.0.value');
    }

    public function ruleIsset(ApiTester $I)
    {
        $I->createAndLoginUser();
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
                    "type" => 'string',
                ],
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
                        ],
                    ],
                ],
            ],
        ]);
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", [' ISSET ' => 8]);
        $I->seeResponseCodeIs(422);

        # invalid request type
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", ['is_set' => 'invalid_type', 'second' => 'test']);
        $I->seeResponseCodeIs(422);

        # invalid request type
        $I->sendPOST("api/v1/tables/{$table->_id}/decisions", ['is_set' => 200, 'second' => false]);
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
                            'matched' => true,
                        ],
                        [
                            'field_key' => 'second',
                            'matched' => true,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function ruleIn(ApiTester $I)
    {
        $I->createAndLoginUser();
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
                    "type" => 'string',
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
                    ],
                ],
            ],
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
                                'matched' => $results[0],
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
                        ],
                    ],
                ]
            );
        }
    }

    public function ruleEqual(ApiTester $I)
    {
        $I->createAndLoginUser();
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
                    "type" => 'boolean',
                ],
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
                        ],
                    ],
                ],
            ],
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
        $I->createAndLoginUser();
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
                    "type" => 'numeric',
                ],
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
                        ],
                    ],
                ],
            ],
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

    public function readList(ApiTester $I)
    {
        $I->createAndLoginUser();
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
        $I->createAndLoginUser();
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
            ],
        ];
        $data['rules'] = [
            [
                'than' => 'Approve',
                'description' => 'New rule',
                'conditions' => [
                    [
                        'field_key' => 'test_key',
                        'condition' => '$eq',
                        'value' => 'test',
                    ],
                ],
            ],
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
                ],
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'description' => 'New rule',
                    'conditions' => [
                        [
                            'field_key' => 'test_key',
                            'condition' => '$eq',
                            'value' => 'test',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function copy(ApiTester $I)
    {
        $I->createAndLoginUser();
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
        $I->createAndLoginUser();
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

    public function analytics(ApiTester $I)
    {
        $checkProbabilities = function ($probabilities, $requests) use ($I) {
            $ruleIndex = 0;
            foreach ($I->getResponseFields()->data->rules as $rule) {
                $conditionIndex = 0;
                foreach ($rule->conditions as $condition) {
                    $I->assertEquals(
                        $probabilities[$ruleIndex][$conditionIndex],
                        $condition->probability,
                        "Wrong probability for {$condition->field_key}:{$condition->condition}=" . var_export($condition->value,
                            true)
                    );

                    $I->assertEquals(
                        is_array($requests) ? $requests[$condition->field_key] : $requests,
                        $condition->requests,
                        "Wrong request amount for condition {$condition->field_key}"
                    );
                    $conditionIndex++;
                }
                $ruleIndex++;
            }
        };

        $I->loginAdmin();

        $tableData = [
            'default_decision' => 'Decline',
            'default_title' => 'Title 100',
            'default_description' => 'Description 220',
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'first',
            'fields' => [
                [
                    "key" => 'numeric',
                    "title" => 'numeric',
                    "source" => "request",
                    "type" => 'numeric',
                    "preset" => [
                        'condition' => '$gte',
                        'value' => 400,
                    ]
                ],
                [
                    "key" => 'string',
                    "title" => 'string',
                    "source" => "request",
                    "type" => 'string',
                ],
                [
                    "key" => 'bool',
                    "title" => 'bool',
                    "source" => "request",
                    "type" => 'boolean',
                ]
            ],
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => 'Valid rule title',
                    'description' => 'Valid rule description',
                    'conditions' => [
                        [
                            'field_key' => 'numeric',
                            'condition' => '$eq',
                            'value' => true
                        ],
                        [
                            'field_key' => 'string',
                            'condition' => '$eq',
                            'value' => 'Yes'
                        ],
                        [
                            'field_key' => 'bool',
                            'condition' => '$eq',
                            'value' => false
                        ]
                    ]
                ],
                [
                    'than' => 'Decline',
                    'title' => 'Second title',
                    'description' => 'Second description',
                    'conditions' => [
                        [
                            'field_key' => 'numeric',
                            'condition' => '$eq',
                            'value' => false
                        ],
                        [
                            'field_key' => 'string',
                            'condition' => '$eq',
                            'value' => 'Not'
                        ],
                        [
                            'field_key' => 'bool',
                            'condition' => '$eq',
                            'value' => true
                        ]
                    ]
                ]
            ]
        ];
        $table = $I->createTable($tableData);

        $checkData = [
            ['numeric' => 340, 'string' => 'Bad', 'bool' => false],
            ['numeric' => 350, 'string' => 'Yes', 'bool' => false],
            ['numeric' => 360, 'string' => 'Not', 'bool' => false],
            ['numeric' => 370, 'string' => 'Yes', 'bool' => false],
            ['numeric' => 380, 'string' => 'Not', 'bool' => false],
            ['numeric' => 390, 'string' => 'Yes', 'bool' => true],
            ['numeric' => 400, 'string' => 'Yes', 'bool' => true],
            ['numeric' => 410, 'string' => 'Not', 'bool' => true],
            ['numeric' => 420, 'string' => 'Bad', 'bool' => true],
        ];
        foreach ($checkData as $data) {
            $I->checkDecision($table->_id, $data);
        }
        $I->sendGET("api/v1/admin/tables/{$table->_id}/analytics");
        $I->assertTableWithAnalytics();

        $checkProbabilities([
            [
                round(3 / 9, 2),
                round(4 / 9, 2),
                round(5 / 9, 2),
            ],
            [
                round(6 / 9, 2),
                round(3 / 9, 2),
                round(4 / 9, 2),
            ],
        ], 9);

        $tableData['fields'][3] = [
            "key" => 'last',
            "title" => 'last',
            "source" => "request",
            "type" => 'numeric',
        ];
        $tableData['rules'][0]['conditions'][] = [
            'field_key' => 'last',
            'condition' => '$lte',
            'value' => 300
        ];
        $tableData['rules'][1]['conditions'][] = [
            'field_key' => 'last',
            'condition' => '$lt',
            'value' => 500
        ];
        $I->sendPUT('api/v1/admin/tables/' . $table->_id, ['table' => $tableData]);
        $I->seeResponseCodeIs(200);

        $checkData = [
            ['numeric' => 380, 'string' => 'Bad', 'last' => 250, 'bool' => false],
            ['numeric' => 390, 'string' => 'Yes', 'last' => 300, 'bool' => false],
            ['numeric' => 400, 'string' => 'Yes', 'last' => 450, 'bool' => true],
            ['numeric' => 410, 'string' => 'Not', 'last' => 550, 'bool' => true],
            ['numeric' => 420, 'string' => 'Bad', 'last' => 650, 'bool' => true],
        ];
        foreach ($checkData as $data) {
            $I->checkDecision($table->_id, $data);
        }

        $I->sendGET("api/v1/admin/tables/{$table->_id}/analytics");
        $I->assertTableWithAnalytics();
        $checkProbabilities([
            [
                round(6 / 14, 2),
                round(6 / 14, 2),
                round(7 / 14, 2),
                round(2 / 5, 2),
            ],
            [
                round(8 / 14, 2),
                round(4 / 14, 2),
                round(7 / 14, 2),
                round(3 / 5, 2),
            ],
        ], [
            'last' => 5,
            'bool' => 14,
            'string' => 14,
            'numeric' => 14,
        ]);
    }
}