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
        $data = $I->getTableShortData();
        $data['fields'] = [
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
        ];
        $data['variants'][0]['rules'] = [
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
        ];
        $table = $I->createTable($data);

        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->assertTable();
        $I->assertResponseDataFields([
            'title' => 'Test title',
            'description' => 'Test description',
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
        $I->assertEquals('test_invalid_key', $table->variants[0]->rules[0]->conditions[0]->field_key);

        # assert preset
        $decision = $I->makeDecision($table->_id, ['test_invalid_key' => 30]);
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

        $decision = $I->makeDecision($table->_id, ['test_invalid_key' => 8]);
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
        $first_user = $I->createUser(true);
        /** Main user, Project admin */
        $second_user = $I->createUser(true);
        /** Test user, Project admin */

        $I->loginUser($first_user);
        $I->createProjectAndSetHeader();
        $table = $I->createTable();
        $I->sendGET('api/v1/admin/tables/' . $table->_id);
        $I->seeResponseCodeIs(200);
        $I->logout();
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

        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'read', 'uncreated_scope']]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('The selected scope is invalid.');

        $I->sendPOST('api/v1/projects/users',
            ['user_id' => $second_user->_id, 'role' => 'manager', 'scope' => ['create', 'read', 'update']]);
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
            'default_decision' => 'Decline',
            'fields' => [
                [
                    "key" => '1',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric',
                    "preset" => null,
                ],
                [
                    "key" => '2',
                    "title" => 'Test 2',
                    "source" => "request",
                    "type" => 'numeric',
                    "preset" => 'shit',
                ],
                [
                    "title" => 'Test 3',
                    "source" => "request",
                    "type" => 'numeric',
                    "preset" => ['invalid' => 'bad'],
                ],
                [
                    "title" => 'Test 3',
                    "source" => "request",
                    "type" => 'numeric',
                    "preset" => ['condition' => '$is_between', 'value' => 'bad'],
                ],
            ],
            'variants' => [
                [
                    'default_title' => str_repeat('2', 129),
                    'default_description' => str_repeat('1', 513),
                    'rules' => [
                        [
                            'than' => 'Approve',
                            'title' => 'Valid rule title',
                            'description' => 'Valid rule description',
                            'conditions' => [
                                [
                                    'field_key' => '3',
                                    'condition' => 'invalid',
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
                                    'value' => true,
                                ],
                                [
                                    'field_key' => '2',
                                    'condition' => '$eq',
                                    'value' => false,
                                ],
                                [
                                    'condition' => '$eq',
                                    'value' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('variants.0.default_title');
        $I->seeResponseContains('variants.0.default_description');
        $I->seeResponseContains('variants.0.rules.1.conditions');
        $I->seeResponseContains('matching_type');
        $I->seeResponseContains('variants.0.rules.0.conditions.0');
        $I->seeResponseContains('variants.0.rules.1.conditions.0');
        $I->seeResponseContains('variants.0.rules.1.conditions.2');
        $I->seeResponseContains('fields.1.preset');
        $I->seeResponseContains('fields.3.preset.condition');
        $I->seeResponseContains('fields.3.preset.condition');
        $I->cantSeeResponseContains('fields.3.preset.value');

        $I->sendPOST('api/v1/admin/tables', [
            'default_decision' => 'Decline',
            'fields' => [
                [
                    "key" => '1',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric',
                ],
            ],
            'variants' => [
                [
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
                ]
            ],
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('variants.0.rules.0.conditions.0.value');

        $I->sendPOST('api/v1/admin/tables', [
            'default_decision' => 'Decline',
            'default_title' => 'Title 100',
            'default_description' => 'Description 220',
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'decision',
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
                    'preset' => null
                ],
                [
                    '_id' => 'invalid',
                    "key" => 'bool',
                    "title" => 'bool',
                    "source" => "request",
                    "type" => 'boolean',
                    'preset' => null
                ]
            ],
            'variants' => [
                [
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
                                    '_id' => 'invalid',
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
                        ]
                    ]
                ]
            ]
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('fields.2._id');
        $I->seeResponseContains('variants.0.rules.0.conditions.1._id');
    }

    public function ruleIsset(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'decision',
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
                    'preset' => null
                ]
            ],
            'variants' => [
                [
                    'default_decision' => 'Decline',
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

        $I->loginConsumer($I->createConsumer());
        $decision = $I->makeDecision($table->_id, ['is_set' => 1000, 'second' => 'test']);

        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->seeResponseCodeIs(401);

        $I->loginUser($user);
        $I->makeDecision($table->_id, ['is_set' => 1000, 'second' => 'test']);

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
            ]
        ]);
    }

    public function ruleIn(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table = $I->createTable([
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'decision',
            'fields' => [
                [
                    "key" => 'test',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'string',
                    'preset' => null
                ],
                [
                    "key" => 'another',
                    "title" => 'Preset wow',
                    "source" => "request",
                    "type" => 'string',
                    "preset" => [
                        'condition' => '$nin',
                        'value' => "1, 3, 'another,comma'",
                    ],
                ],
                [
                    "key" => 'more',
                    "title" => 'Preset third',
                    "source" => "request",
                    "type" => 'string',
                    "preset" => [
                        'condition' => '$in',
                        'value' => "1, 3, 'third,comma'",
                    ],
                ],
            ],
            'variants' => [
                [
                    'default_decision' => 'Decline',
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
                                    'field_key' => 'another',
                                    'condition' => '$eq',
                                    'value' => true,
                                ],
                                [
                                    'field_key' => 'more',
                                    'condition' => '$eq',
                                    'value' => true,
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ]);
        $data = [
            'wow,comma' => [true, true, false],
            'another,comma' => [false, false, false],
            'third,comma' => [false, true, true],
        ];
        foreach ($data as $value => $results) {
            $id = $I->makeDecision($table->_id, ['test' => $value, 'another' => $value, 'more' => $value])->_id;
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
                                'field_key' => 'another',
                                'condition' => '$eq',
                                'matched' => $results[1],
                            ],
                            [
                                'field_key' => 'more',
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
            'matching_type' => 'decision',
            'fields' => [
                [
                    "key" => 'boolean',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'boolean',
                    'preset' => null
                ]
            ],
            'variants' => [
                [
                    'default_decision' => 'Decline',
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
                ]
            ]
        ];

        # boolean
        $table = $I->createTable($table_data);
        foreach ([true, '1', 1] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Approve']);
        }
        foreach ([false, '0', 0, null] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach (['invalid', 'true', "true", 100] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }

        # string
        $table_data['fields'][0]['type'] = 'string';
        $table_data['variants'][0]['rules'][0]['conditions'][0]['value'] = 'string';
        $table = $I->createTable($table_data);
        foreach ([true, 1, false, 0.99] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach (['invalid', '123321', 'true', "true"] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        $I->makeDecision($table->_id, ['boolean' => 'string']);
        $I->assertResponseDataFields(['final_decision' => 'Approve']);

        # numeric
        $table_data['fields'][0]['type'] = 'numeric';
        $table_data['variants'][0]['rules'][0]['conditions'][0]['value'] = 100.15;
        $table = $I->createTable($table_data);
        foreach ([true, 'invalid', '100.15i'] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach ([100, "100"] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach ([100.15, "100.15"] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
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
            'matching_type' => 'decision',
            'fields' => [
                [
                    "key" => 'boolean',
                    "title" => 'Test',
                    "source" => "request",
                    "type" => 'numeric',
                    'preset' => null
                ]
            ],
            'variants' => [
                [
                    'default_decision' => 'Decline',
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
                ]
            ]
        ]);
        foreach ([true, 'invalid', '100.15i'] as $value) {
            $I->sendPOST("api/v1/tables/$table->_id/decisions", ['boolean' => $value]);
            $I->seeResponseCodeIs(422);
        }
        foreach ([100, "100"] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Decline']);
        }
        foreach ([100.15, "100.15"] as $value) {
            $I->makeDecision($table->_id, ['boolean' => $value]);
            $I->assertResponseDataFields(['final_decision' => 'Approve']);
        }
    }

    public function ruleBetween(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $tableData = [
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'decision',
            'fields' => [
                [
                    "key" => 'between',
                    "title" => 'Second',
                    "source" => "request",
                    "type" => 'numeric',
                    'preset' => null
                ]
            ],
            'variants' => [
                [
                    'default_decision' => 'Decline',
                    'rules' => [
                        [
                            'than' => 'Approve',
                            'title' => '',
                            'description' => '',
                            'conditions' => [
                                [
                                    'field_key' => 'between',
                                    'condition' => '$between',
                                    'value' => '0.5;5,5',
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $table = $I->createTable($tableData);
        $I->seeResponseContains('0.5;5,5');

        $data = [
            '0.5' => 'Approve',
            '0.444445' => 'Decline',
            '5.5' => 'Approve',
            '5.6' => 'Decline',
        ];
        foreach ($data as $value => $result) {
            $decision = $I->makeDecision($table->_id, ['between' => floatval($value)]);
            $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
            $I->assertResponseDataFields(['final_decision' => $result]);
        }

        foreach (['1.223.33', 100.01, '10;8', '1;2;3', '3,3'] as $item) {
            $tableData['rules'][0]['conditions'][0]['value'] = $item;
            $I->sendPOST('api/v1/admin/tables', ['table' => $tableData]);
            $I->seeResponseCodeIs(422);
        }
    }

    public function readList(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();

        $tableData = $I->getTableShortData();
        $tableData['title'] = 'Search';
        $tableData['description'] = 'Concrete';
        $table1 = $I->createTable($tableData);

        $tableData['title'] = 'Search';
        $tableData['description'] = 'Another';
        $table2 = $I->createTable($tableData);

        $tableData['title'] = 'caps';
        $tableData['description'] = 'EXAMPLE';
        $table3 = $I->createTable($tableData);

        $tableData['title'] = 'mank@34ind';
        $tableData['description'] = 'example';
        $table4 = $I->createTable($tableData);

        $I->sendGET('api/v1/admin/tables');
        $I->assertListTable();
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/tables/' . $item->_id);
            $I->assertTable();
        }

        $queries = [
            'title=arc' => [
                'amount' => 2,
                'ids' => [$table1->_id, $table2->_id]
            ],
            'title=@34' => [
                'amount' => 1,
                'ids' => [$table4->_id]
            ],
            'description=EXA' => [
                'amount' => 2,
                'ids' => [$table3->_id, $table4->_id]
            ],
        ];
        foreach ($queries as $query => $data) {
            $I->sendGET("api/v1/admin/tables?$query");
            $I->assertListTable();

            $response = $I->getResponseFields()->data;
            $I->assertEquals($data['amount'], count($response), "Wrong amount of the tables for query $query");
            for ($i = 0; $i < count($response); ++$i) {
                $id = $response[$i]->_id;
                $I->assertTrue(in_array($id, $data['ids']), "Id '$id' should not be in response for query $query");
                unset($data['ids'][$i]);
            }
            $I->assertTrue(
                0 == count($data['ids']),
                "Next ids should be in response: " . implode(',', $data['ids']) . ". Query: $query"
            );
        }

        $I->logout();
        $I->sendGET('api/v1/admin/tables');
        $I->seeResponseCodeIs(401);
    }

    public function update(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
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
                'preset' => null
            ]
        ];
        $data['variants'] = [
            [
                'default_decision' => 'Decline',
                'rules' => [
                    [
                        '_id' => strval(new MongoId),
                        'than' => 'Approve',
                        'description' => 'New rule',
                        'conditions' => [
                            [
                                '_id' => strval(new MongoId),
                                'field_key' => 'test_key',
                                'condition' => '$eq',
                                'value' => 'test',
                            ],
                        ],
                    ],
                ]
            ]
        ];
        $I->sendPUT('api/v1/admin/tables/' . $id, $data);
        $I->assertTable();
        $I->assertResponseDataFields($data);

        $I->sendGET('api/v1/admin/changelog/tables');
        $I->assertResponseDataFields(['author' => $user->username]);
    }

    public function copy(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable($I->getTableShortData());

        $data = $I->getResponseFields()->data;
        $id = $data->_id;
        $I->sendPOST("api/v1/admin/tables/$id/copy", []);
        $I->assertTable();
        $cloneData = $I->getResponseFields()->data;
        unset($cloneData->_id);
        unset($cloneData->variants[0]->_id);
        unset($data->_id);
        unset($data->variants[0]->_id);

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
        $checkProbabilities = function ($probabilities, $requestsConditions, $requestsRule) use ($I) {
            $ruleIndex = 0;
            foreach ($I->getResponseFields()->data->variants[0]->rules as $rule) {
                $conditionIndex = 0;
                foreach ($rule->conditions as $condition) {
                    $I->assertEquals(
                        $probabilities[$ruleIndex]['conditions'][$conditionIndex],
                        $condition->probability,
                        "Wrong probability for condition {$condition->field_key}:{$condition->condition}=" .
                        var_export(
                            $condition->value,
                            true
                        )
                    );

                    $I->assertEquals(
                        is_array($requestsConditions) ? $requestsConditions[$condition->field_key] : $requestsConditions,
                        $condition->requests,
                        "Wrong request amount for condition {$condition->field_key}"
                    );
                    $conditionIndex++;
                }
                $I->assertEquals(
                    $probabilities[$ruleIndex]['rule'],
                    $rule->probability,
                    "Wrong probability for rule {$rule->title}"
                );
                $I->assertEquals(
                    is_array($requestsRule) ? $requestsRule[$ruleIndex] : $requestsRule,
                    $rule->requests,
                    "Wrong request amount for rule {$rule->title}"
                );
                $ruleIndex++;
            }
        };

        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();

        $variantId1 = new MongoId;
        $variantId2 = new MongoId;

        $tableData = $I->getTableShortData();
        $tableData['variants'][0]['_id'] = $variantId1;
        $tableData['variants'][] = [
            '_id' => $variantId2,
            'default_decision' => 'Decline',
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
                ]
            ]
        ];
        $table = $I->createTable($tableData);


        $checkData = [
            ['numeric' => 340, 'string' => 'Bad', 'bool' => true],
            ['numeric' => 350, 'string' => 'Yes', 'bool' => false],
            ['numeric' => 360, 'string' => 'Not', 'bool' => false],
            ['numeric' => 370, 'string' => 'Yes', 'bool' => false],
            ['numeric' => 380, 'string' => 'Not', 'bool' => false],
            ['numeric' => 390, 'string' => 'Yes', 'bool' => true],
            ['numeric' => 400, 'string' => 'Bad', 'bool' => true],
            ['numeric' => 410, 'string' => 'Not', 'bool' => true],
            ['numeric' => 420, 'string' => 'Yes', 'bool' => false],
        ];
        foreach ($checkData as $data) {
            $data['variant_id'] = $variantId1;
            $I->makeDecision($table->_id, $data, 'decision');
        }
        $I->sendGET("api/v1/admin/tables/{$table->_id}/$variantId1/analytics");
        $I->assertTableWithAnalytics();

        $checkProbabilities([
            [
                'rule' => round(1 / 9, 5),
                'conditions' => [
                    round(3 / 9, 5),
                    round(4 / 9, 5),
                    round(5 / 9, 5),
                ]
            ],
            [
                'rule' => 0,
                'conditions' => [
                    round(6 / 9, 5),
                    round(3 / 9, 5),
                    round(4 / 9, 5),
                ]
            ],
        ], 9, 9);

        $tableData['fields'][3] = [
            "key" => 'last',
            "title" => 'last',
            "source" => "request",
            "type" => 'numeric',
            'preset' => null
        ];
        $tableData['variants'][0]['rules'][0]['conditions'][] = [
            'field_key' => 'last',
            'condition' => '$lte',
            'value' => 300
        ];
        $tableData['variants'][0]['rules'][1]['conditions'][] = [
            'field_key' => 'last',
            'condition' => '$lt',
            'value' => 500
        ];
        $tableData['variants'][1]['rules'][0]['conditions'][] = [
            'field_key' => 'last',
            'condition' => '$lt',
            'value' => 500
        ];
        $I->sendPUT('api/v1/admin/tables/' . $table->_id, $tableData);
        $I->seeResponseCodeIs(200);

        $checkData = [
            ['numeric' => 380, 'string' => 'Not', 'last' => 250, 'bool' => true],
            ['numeric' => 390, 'string' => 'Not', 'last' => 300, 'bool' => true],
            ['numeric' => 400, 'string' => 'Yes', 'last' => 450, 'bool' => false],
            ['numeric' => 410, 'string' => 'Bad', 'last' => 550, 'bool' => false],
            ['numeric' => 420, 'string' => 'Bad', 'last' => 650, 'bool' => true],
        ];
        foreach ($checkData as $data) {
            $data['variant_id'] = $variantId1;
            $I->makeDecision($table->_id, $data);
        }

        $I->sendGET("api/v1/admin/tables/{$table->_id}/$variantId1/analytics");
        $I->assertTableWithAnalytics();
        $checkProbabilities([
            [
                'rule' => round(1 / 14, 5),
                'conditions' => [
                    round(6 / 14, 5),
                    round(5 / 14, 5),
                    round(7 / 14, 5),
                    round(2 / 5, 5),
                ]
            ],
            [
                'rule' => round(2 / 14, 5),
                'conditions' => [
                    round(8 / 14, 5),
                    round(5 / 14, 5),
                    round(7 / 14, 5),
                    round(3 / 5, 5),
                ]
            ],
        ], [
            'last' => 5,
            'bool' => 14,
            'string' => 14,
            'numeric' => 14,
        ], 14);

        $I->sendGET("api/v1/admin/tables/{$table->_id}/$variantId2/analytics");
        $I->assertTableWithAnalytics();
        $checkProbabilities(
            [['rule' => 0, 'conditions' => [0, 0, 0, 0]]],
            ['last' => 0, 'bool' => 0, 'string' => 0, 'numeric' => 0,], 0
        );
        $I->sendGET("api/v1/admin/tables/{$table->_id}/invalidId/analytics");
        $I->seeResponseCodeIs(404);
    }

    public function filters(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();

        $table = $I->createTable($I->getTableShortData());

        $tableDataAll = $I->getShortTableDataMatchingTypeAll();
        $tableDataAll['description'] = 'Matching type scoring';
        $tableAll = $I->createTable($tableDataAll);

        $I->sendGET('api/v1/admin/tables?title=Title');
        $I->assertTrue(count($I->getResponseFields()->data) == 2, "Wrong amount of the Tables by filter 'Title'");

        $I->sendGET('api/v1/admin/tables?description=cription');
        $I->assertEquals($table->_id, $I->getResponseFields()->data[0]->_id);

        $I->sendGET('api/v1/admin/tables?description=ching');
        $I->assertEquals($tableAll->_id, $I->getResponseFields()->data[0]->_id);

        $I->sendGET('api/v1/admin/tables?matching_type=invalid');
        $I->seeResponseCodeIs(422);

        $I->sendGET('api/v1/admin/tables?matching_type=scoring');
        $I->assertEquals($tableAll->_id, $I->getResponseFields()->data[0]->_id);

        $I->sendGET('api/v1/admin/tables?matching_type=decision');
        $I->assertEquals($table->_id, $I->getResponseFields()->data[0]->_id);

        $secondProject = $I->createProject(true);
        $I->setHeader('X-Application', $secondProject->_id);
        $I->sendGET('api/v1/admin/tables');
        $I->assertEquals(0, count($I->getResponseFields()->data));
    }

    public function testVariantsProbabilityPercent(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();

        $data = $I->getTableShortData();
        $data['variants_probability'] = 'percent';
        $data['variants'][0]['probability'] = 10;
        $data['variants'][0]['title'] = 'Variant 1';
        $data['variants'][1] = [
            'title' => 'Variant 2',
            'default_title' => 'Variant 2',
            'default_description' => 'Description Variant 2',
            'default_decision' => 'Decline',
            'probability' => 40,
            'rules' => $I->getVariantRules(),
        ];
        $data['variants'][2] = [
            'title' => 'Variant 3',
            'default_title' => 'Variant 3',
            'default_description' => 'Description Variant 3',
            'default_decision' => 'Decline',
            'probability' => 50,
            'rules' => $I->getVariantRules(),
        ];

        $matched = [];
        $table = $I->createTable($data);
        for ($i = 0; $i < 100; $i++) {
            $res = $I->makeDecision($table->_id, ['numeric' => 340, 'string' => 'Bad', 'bool' => true]);
            $title = $res->table->variant->title;
            if (!array_key_exists($title, $matched)) {
                $matched[$title] = 0;
            }
            $matched[$title]++;
        }
        $I->assertEquals(3, count($matched), 'Some Table.variants for variants_probability=percent not matched');
        $I->assertTrue($matched['Variant 1'] < $matched['Variant 2'], 'Variant 1 checked more than Variant 2');
        $I->assertTrue($matched['Variant 1'] < $matched['Variant 3'], 'Variant 1 checked more than Variant 3');
    }

    public function testVariantsProbabilityInvalid(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();

        # invalid variants_probability
        $data = $I->getTableShortData();
        $data['variants_probability'] = 'invalid';
        $I->sendPOST('api/v1/admin/tables', $data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('variants_probability');

        # more than 100
        $data['variants_probability'] = 'percent';
        $data['variants'][0]['probability'] = 30;
        $data['variants'][0]['title'] = 'Variant 1';
        $data['variants'][1] = [
            'title' => 'Variant 2',
            'default_title' => 'Variant 2',
            'default_description' => 'Description Variant 2',
            'default_decision' => 'Decline',
            'probability' => 71,
            'rules' => $I->getVariantRules(),
        ];
        $I->sendPOST('api/v1/admin/tables', $data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('variants_probability');

        # less than 100
        $data['variants'][0]['probability'] = 28;
        $I->sendPOST('api/v1/admin/tables', $data);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContains('variants_probability');
    }
}
