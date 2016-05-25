<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    private $tableData;
    private $mongo;
    private $client;
    private $user;
    private $project;

    use _generated\ApiTesterActions;


    public function __construct(\Codeception\Scenario $scenario)
    {
        $this->scenario = $scenario;
        $this->scenario->stopIfBlocked();
    }

    public function createGroup($tablesAmount = 2, $probability = 'random', array $data = null)
    {
        if (!$data) {
            $data['tables'] = [];
            for ($i = 0; $i < $tablesAmount; $i++) {
                $data['tables'][$i] = [
                    '_id' => $this->createTable(null, false)->_id,
                ];
            }
        }
        if (!isset($data['title'])) {
            $data['title'] = 'Group title';
        }
        if (!isset($data['description'])) {
            $data['description'] = 'Group description';
        }
        $data['probability'] = $probability;
        $this->sendPOST('api/v1/admin/groups', $data);
        $this->assertGroup('$.data', 201);

        return $this->getResponseFields()->data;
    }

    public function getFaker($locale = 'en_US')
    {
        return Faker\Factory::create($locale);
    }

    public function assertGroup($jsonPath = '$.data', $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'tables' => 'array',
            'probability' => 'string:regex(@^(random)$@)',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.tables[*]");
    }

    public function assertListGroup($jsonPath = '$.data[*]')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'tables' => 'array',
            'probability' => 'string:regex(@^(random)$@)',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
        ], "$jsonPath.tables[*]");
    }

    public function createTable(array $data = null, $assert = true)
    {
        $this->sendPOST('api/v1/admin/tables', ['table' => $data ?: $this->getTableData()]);
        if ($assert) {
            $this->assertTable('$.data', 201);
        }

        return $this->getResponseFields()->data;
    }

    public function assertListTable($jsonPath = '$.data[*]')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'default_decision' => 'string|integer',
        ], $jsonPath);

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.fields");

    }

    public function assertTable($jsonPath = '$.data', $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'default_decision' => 'string|integer',
            'default_title' => 'string',
            'default_description' => 'string',
            'rules' => 'array',
            'fields' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'key' => 'string:regex(@^[a-z_]+$@)',
            'title' => 'string',
            'source' => 'string',
            'type' => 'string',
            'preset' => 'null|array',
        ], "$jsonPath.fields[*]");

        foreach ($this->getResponseFields()->data->fields as $field) {
            if (is_array($field->preset)) {
                foreach (['value', 'condition'] as $item) {
                    $this->assertTrue(array_key_exists($item, $field->preset), "Preset must contains '$item' field");
                }
            }
        }

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'than' => 'string|integer|float',
            'description' => 'string',
            'conditions' => 'array',
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'field_key' => 'string',
            'condition' => 'string',
            'value' => 'string|integer|float|boolean',
        ], "$jsonPath.rules[*].conditions[*]");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions[*].matched");
    }

    public function assertTableWithAnalytics($jsonPath = '$.data', $code = 200)
    {
        $this->assertTable($jsonPath, $code);
        $this->seeResponseMatchesJsonType([
            'probability' => 'integer|float',
            'requests' => 'integer'
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            'probability' => 'integer|float',
            'requests' => 'integer'
        ], "$jsonPath.rules[*].conditions[*]");
    }

    public function assertTableDecisionsForAdmin($matching_rules_type = 'first', $jsonPath = '$.data')
    {
        $type = $matching_rules_type == 'all' ? 'integer|float' : 'string';
        $this->seeResponseCodeIs(200);
        $rules = [
            '_id' => 'string',
            'table' => 'array',
            'group' => 'array|null',
            'meta' => 'array',
            'default_decision' => $type,
            'final_decision' => $type,
            'updated_at' => 'string',
            'created_at' => 'string',
            'rules' => 'array',
            'fields' => 'array',
            'request' => 'array',
        ];
        if ($matching_rules_type == 'first') {
            $rules['title'] = 'string';
            $rules['description'] = 'string';
        }
        $this->seeResponseMatchesJsonType($rules, $jsonPath);

        $this->seeResponseMatchesJsonType([
            'key' => 'string',
            'title' => 'string',
            'source' => 'string',
            'type' => 'string',
        ], "$jsonPath.fields[*]");

        $this->seeResponseMatchesJsonType([
            'than' => $type,
            'decision' => "$type|null",
            'title' => 'string',
            'description' => 'string',
            'conditions' => 'array',
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            'field_key' => 'string',
            'condition' => 'string',
            'value' => 'string|integer|float|boolean',
            'matched' => 'boolean',
        ], "$jsonPath.rules[*].conditions[*]");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.table");
    }

    public function assertTableDecisionsForConsumer($matching_rules_type = 'first', $jsonPath = '$.data')
    {
        $type = $matching_rules_type == 'all' ? 'integer|float' : 'string';
        $this->seeResponseCodeIs(200);
        $rules = [
            '_id' => 'string',
            'table' => 'array',
            'final_decision' => $type,
            'request' => 'array',
            'rules' => 'array',
        ];
        if ($matching_rules_type == 'first') {
            $rules['title'] = 'string';
            $rules['description'] = 'string';
        }
        $this->seeResponseMatchesJsonType($rules, $jsonPath);

        $this->seeResponseMatchesJsonType([
            'decision' => "$type|null",
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.table");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.fields");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.group");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.meta");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.default_decision");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].than");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions");
    }

    public function checkDecision($table_id, array $data = [], $matching_rules_type = 'first', $route = 'tables')
    {
        $data = $data ?: [
            'borrowers_phone_verification' => 'Positive',
            'contact_person_phone_verification' => 'Positive',
            'internal_credit_history' => 'Positive',
            'employment' => true,
            'property' => true,
        ];
        if (!array_key_exists('matching_rules_type', $data)) {
            $data['matching_rules_type'] = $matching_rules_type;
        }
        $this->sendPOST("api/v1/$route/$table_id/decisions", $data);
        $this->assertTableDecisionsForConsumer($matching_rules_type);

        return $this->getResponseFields()->data;
    }

    public function getMongoId()
    {
        return strval(new MongoId);
    }

    public function getTableShortData()
    {
        return [
            'default_decision' => 'Decline',
            'default_title' => 'Title 100',
            'default_description' => 'Description 220',
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'first',
            'fields' => [
                [
                    "_id" => $this->getMongoId(),
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
                    "_id" => $this->getMongoId(),
                    "key" => 'string',
                    "title" => 'string',
                    "source" => "request",
                    "type" => 'string',
                    'preset' => null
                ],
                [
                    "_id" => $this->getMongoId(),
                    "key" => 'bool',
                    "title" => 'bool',
                    "source" => "request",
                    "type" => 'boolean',
                    'preset' => null
                ]
            ],
            'rules' => [
                [
                    "_id" => $this->getMongoId(),
                    'than' => 'Approve',
                    'title' => 'Valid rule title',
                    'description' => 'Valid rule description',
                    'conditions' => [
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'numeric',
                            'condition' => '$eq',
                            'value' => true
                        ],
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'string',
                            'condition' => '$eq',
                            'value' => 'Yes'
                        ],
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'bool',
                            'condition' => '$eq',
                            'value' => false
                        ]
                    ]
                ],
                [
                    "_id" => $this->getMongoId(),
                    'than' => 'Decline',
                    'title' => 'Second title',
                    'description' => 'Second description',
                    'conditions' => [
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'numeric',
                            'condition' => '$eq',
                            'value' => false
                        ],
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'string',
                            'condition' => '$eq',
                            'value' => 'Not'
                        ],
                        [
                            "_id" => $this->getMongoId(),
                            'field_key' => 'bool',
                            'condition' => '$eq',
                            'value' => true
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getShortTableDataMatchingTypeAll()
    {
        $tableData = $this->getTableShortData();
        $tableData['matching_type'] = 'all';
        $tableData['default_decision'] = 15;
        $tableData['rules'] = [
            [
                'than' => 100,
                'title' => 'Valid rule title',
                'description' => 'Valid rule description',
                'conditions' => [
                    [
                        'field_key' => 'numeric',
                        'condition' => '$eq',
                        'value' => true,
                        'preset' => null
                    ],
                    [
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Yes',
                        'preset' => null
                    ],
                    [
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => false,
                        'preset' => null
                    ]
                ]
            ],
            [
                'than' => -50.74445,
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
            ],
            [
                'than' => 25.24445,
                'title' => 'Third title',
                'description' => 'Third description',
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
        ];

        return $tableData;
    }

    public function getTableData()
    {
        if (!$this->tableData) {
            $this->tableData = $this->parseCsv();
        }

        return $this->tableData;
    }

    private function parseCsv()
    {
        $csv = array_map('str_getcsv', file(__DIR__ . '/../_data/decisions-tables.csv'));

        array_walk($csv, function (&$row) use ($csv) {
            $row = array_combine(
                array_map('trim', explode(';', $csv[0][0])),
                array_map('trim', explode(';', $row[0]))
            );
        });

        $fields = array_shift($csv);

        $data = [
            'default_decision' => 'Decline',
            'title' => 'Test title',
            'matching_type' => 'first',
            'description' => 'Test description',
            'fields' => [],
            'rules' => [],
        ];

        unset($fields['Than']);
        foreach ($fields as $field) {
            $type = 'string';
            if (in_array($field, ['Employment', 'Property'])) {
                $type = 'boolean';
            }
            $key = strtolower(str_replace(' ', '_', $field));
            $data['fields'][] = [
                '_id' => $this->getMongoId(),
                "key" => $key,
                "title" => $field,
                "source" => "request",
                "type" => $type,
                "preset" => null,
            ];
        }
        foreach ($csv as $rule) {
            $than = $rule['Than'];
            unset($rule['Than']);

            $conditions = [];
            foreach ($rule as $key => $value) {
                if ($value == 'y') {
                    $value = true;
                } elseif ($value == 'n') {
                    $value = false;
                }
                $conditions[] = [
                    '_id' => $this->getMongoId(),
                    'field_key' => strtolower(str_replace(' ', '_', $key)),
                    'condition' => '$eq',
                    'value' => $value,
                ];
            }
            $data['rules'][] = [
                '_id' => $this->getMongoId(),
                'than' => $than,
                'title' => '',
                'description' => '',
                'conditions' => $conditions,
            ];
        }

        return $data;
    }

    public function assertProject($jsonPath = '$.data', $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'users' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'user_id' => 'string',
            'role' => 'string',
            'scope' => 'array',
        ], "$jsonPath.users[*]");

        $this->canSeeResponseJsonMatchesJsonPath("$jsonPath.consumers");
    }

    public function assertResponseDataFields(array $fields, $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseIsJson();
        $this->seeResponseContains('data');
        $this->seeResponseContainsJson([
            'meta' => ['code' => $code],
            'data' => $fields,
        ]);
    }

    public function getResponseFields()
    {
        return json_decode($this->grabResponse());
    }

    public function loginAdmin()
    {
        $this->amHttpAuthenticated('admin', 'admin');
    }

    public function loginConsumer($consumer)
    {
        $this->logout();
        $this->setHeader('Authorization',
            'Basic ' . base64_encode($consumer->client_id . ':' . $consumer->client_secret));
    }

    public function createConsumer()
    {
        $this->sendPOST('api/v1/projects/consumers',
            ['description' => $this->getFaker()->text('20'), 'scope' => ['check']]);

        return json_decode($this->grabResponse())->data->consumers[0];
    }

    public function getMongo()
    {
        if (!$this->mongo) {
            $this->mongo = (new MongoClient())->selectDB('gandalf_test');
        }

        return $this->mongo;
    }

    public function createProjectAndSetHeader()
    {
        $project = $this->createProject();
        $this->setHeader('X-Application', $project->_id);

        return $project;
    }

    public function createProject($new = false)
    {
        if (!$this->project || $new) {
            $faker = $this->getFaker();
            $project = [
                'title' => $faker->streetName,
                'description' => $faker->text('150'),
            ];
            $this->sendPOST('api/v1/projects', $project);
            $project = json_decode($this->grabResponse());
            $this->assertProject('$.data', 201);
            $this->project = $project->data;
        }

        return $this->project;
    }

    public function createUser($new = false)
    {
        $this->createAndLoginClient();
        if (!$this->user || $new) {
            $faker = $this->getFaker();

            $user_data = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => $faker->password(),
                'username' => $faker->firstName,
            ];

            $this->sendPOST('api/v1/users/', $user_data);
            $this->seeResponseCodeIs(201);
            $response = json_decode($this->grabResponse());
            $user_info = $response->data;


            $this->sendPOST('api/v1/users/verify/email', ['token' => $response->sandbox->token_email->token]);
            $this->seeResponseCodeIs(200);

            $this->sendPOST('api/v1/oauth/',
                [
                    'grant_type' => 'password',
                    'username' => $user_data['username'],
                    'password' => $user_data['password'],
                ]
            );

            $user_info->token = json_decode($this->grabResponse());
            $this->user = $user_info;
        }

        return $this->user;
    }

    public function createAndLoginUser()
    {
        $user = $this->createUser();
        $this->loginUser($user);

        return $user;
    }

    public function loginUser($user)
    {
        $this->logout();
        $this->setHeader('Authorization', 'Bearer ' . $user->token->access_token);
    }

    public function createAndLoginClient()
    {
        if (!$this->client) {
            $faker = $this->getFaker();
            $client = [
                'client_id' => md5($faker->name),
                'client_secret' => $faker->password(32, 32),
            ];
            $this->getMongo()->oauth_clients->insert($client);
            $this->client = $client;
        }
        $this->loginClient($this->client);

        return $this->client;
    }

    public function loginClient($client)
    {
        $this->setHeader('Authorization',
            'Basic ' . base64_encode($client['client_id'] . ':' . $client['client_secret']));
    }

    public function logout()
    {
        $this->deleteHeader('Authorization');
    }

    public function stdToArray($std)
    {
        return json_decode(json_encode($std), true);
    }

    public function removeIdsFromArray(array $array)
    {
        return array_map(function ($item) {
            unset($item['_id']);

            return $item;
        }, $array);
    }
}
