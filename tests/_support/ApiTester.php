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
    }

    public function getFaker($locale = 'en_US')
    {
        return Faker\Factory::create($locale);
    }

    public function createTable(array $data = null, $assert = true)
    {
        $this->sendPOST('api/v1/admin/tables', $data ?: $this->getTableData());
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
            'matching_type' => 'string',
            'variants' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.variants[*]");

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
            'variants' => 'array',
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
            'title' => 'string',
            'default_decision' => 'string|integer',
            'default_title' => 'string',
            'default_description' => 'string',
        ], "$jsonPath.variants[*]");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'than' => 'string|integer|float',
            'description' => 'string',
            'conditions' => 'array',
        ], "$jsonPath.variants[*].rules[*]");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'field_key' => 'string',
            'condition' => 'string',
            'value' => 'string|integer|float|boolean',
        ], "$jsonPath.variants[*].rules[*].conditions[*]");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.variants[*].rules[*].conditions[*].matched");
    }

    public function assertTableWithAnalytics($jsonPath = '$.data', $code = 200)
    {
        $this->assertTable($jsonPath, $code);
        $this->seeResponseMatchesJsonType([
            'probability' => 'integer|float|null',
            'requests' => 'integer',
        ], "$jsonPath.variants[*].rules[*]");

        $this->seeResponseMatchesJsonType([
            'probability' => 'integer|float|null',
            'requests' => 'integer',
        ], "$jsonPath.variants[*].rules[*].conditions[*]");
    }

    public function assertTableDecisionsForAdmin($matching_rules_type = 'decision', $jsonPath = '$.data')
    {
        $type = $matching_rules_type == 'scoring' ? 'integer|float' : 'string';
        $this->seeResponseCodeIs(200);
        $rules = [
            '_id' => 'string',
            'table' => 'array',
            'meta' => 'array',
            'default_decision' => $type,
            'final_decision' => $type,
            'updated_at' => 'string',
            'created_at' => 'string',
            'rules' => 'array',
            'fields' => 'array',
            'request' => 'array',
        ];
        if ($matching_rules_type == 'decision') {
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
            'variant' => 'array',
            'matching_type' => 'string',
        ], "$jsonPath.table");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.table.variant");
    }

    public function assertTableDecisionsForConsumer(
        $matching_rules_type = 'decision',
        $showMeta = true,
        $jsonPath = '$.data'
    ) {
        $type = $matching_rules_type == 'scoring' ? 'integer|float' : 'string';
        $this->seeResponseCodeIs(200);
        $rules = [
            '_id' => 'string',
            'table' => 'array',
            'final_decision' => $type,
            'request' => 'array',
            'created_at' => 'string',
            'updated_at' => 'string',
        ];
        if ($showMeta) {
            $rules['rules'] = 'array';
        }
        if ($matching_rules_type == 'decision') {
            $rules['title'] = 'string';
            $rules['description'] = 'string';
        }
        $this->seeResponseMatchesJsonType($rules, $jsonPath);

        if ($showMeta) {
            $this->seeResponseMatchesJsonType([
                'decision' => "$type|null",
                'title' => 'string',
                'description' => 'string',
            ], "$jsonPath.rules[*]");
            $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].than");
            $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions");
        } else {
            $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules");
        }

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'matching_type' => 'string',
            'variant' => 'array',
        ], "$jsonPath.table");

        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
        ], "$jsonPath.table.variant");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.fields");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.group");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.meta");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.default_decision");
    }

    public function makeDecision($table_id, array $data = [], $matching_rules_type = 'decision', $showMeta = true)
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
        $this->sendPOST("api/v1/tables/$table_id/decisions", $data);
        $this->assertTableDecisionsForConsumer($matching_rules_type, $showMeta);

        return $this->getResponseFields()->data;
    }

    public function getMongoId()
    {
        return strval(new MongoDB\BSON\ObjectId);
    }

    public function getTableShortData()
    {
        return [
            'title' => 'Test title',
            'description' => 'Test description',
            'matching_type' => 'decision',
            'decision_type' => 'alpha_num',
            'variants_probability' => 'first',
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
                    ],
                ],
                [
                    "_id" => $this->getMongoId(),
                    "key" => 'string',
                    "title" => 'string',
                    "source" => "request",
                    "type" => 'string',
                    'preset' => null,
                ],
                [
                    "_id" => $this->getMongoId(),
                    "key" => 'bool',
                    "title" => 'bool',
                    "source" => "request",
                    "type" => 'boolean',
                    'preset' => null,
                ],
            ],
            'variants' => [
                [
                    'title' => 'Variant title',
                    'description' => 'Variant description',
                    'default_title' => 'Title 100',
                    'default_description' => 'Description 220',
                    'default_decision' => 'Decline',
                    'rules' => $this->getVariantRules(),
                ],
            ],
        ];
    }

    public function getVariantRules()
    {
        return [
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
                        'value' => true,
                    ],
                    [
                        "_id" => $this->getMongoId(),
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Yes',
                    ],
                    [
                        "_id" => $this->getMongoId(),
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => false,
                    ],
                ],
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
                        'value' => false,
                    ],
                    [
                        "_id" => $this->getMongoId(),
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Not',
                    ],
                    [
                        "_id" => $this->getMongoId(),
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => true,
                    ],
                ],
            ],
        ];
    }

    public function getShortTableDataMatchingTypeAll()
    {
        $tableData = $this->getTableShortData();
        $tableData['decision_type'] = 'numeric';
        $tableData['matching_type'] = 'scoring';
        $tableData['variants'][0]['default_decision'] = 15;
        $tableData['variants'][0]['rules'] = [
            [
                'than' => 100,
                'title' => 'Valid rule title',
                'description' => 'Valid rule description',
                'conditions' => [
                    [
                        'field_key' => 'numeric',
                        'condition' => '$eq',
                        'value' => true,
                        'preset' => null,
                    ],
                    [
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Yes',
                        'preset' => null,
                    ],
                    [
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => false,
                        'preset' => null,
                    ],
                ],
            ],
            [
                'than' => -50.74445,
                'title' => 'Second title',
                'description' => 'Second description',
                'conditions' => [
                    [
                        'field_key' => 'numeric',
                        'condition' => '$eq',
                        'value' => false,
                    ],
                    [
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Not',
                    ],
                    [
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => true,
                    ],
                ],
            ],
            [
                'than' => 25.24445,
                'title' => 'Third title',
                'description' => 'Third description',
                'conditions' => [
                    [
                        'field_key' => 'numeric',
                        'condition' => '$eq',
                        'value' => false,
                    ],
                    [
                        'field_key' => 'string',
                        'condition' => '$eq',
                        'value' => 'Not',
                    ],
                    [
                        'field_key' => 'bool',
                        'condition' => '$eq',
                        'value' => true,
                    ],
                ],
            ],
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
            'title' => 'Test title',
            'description' => 'Test description',
            'decision_type' => 'alpha_num',
            'matching_type' => 'decision',
            'variants_probability' => '',
            'fields' => [],
            'variants' => [
                [
                    '_id' => $this->getMongoId(),
                    'default_decision' => 'Decline',
                    'rules' => [],
                ],
            ],
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
            $data['variants'][0]['rules'][] = [
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
            'description' => 'string',
            'settings' => 'array',
            'users' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'user_id' => 'string',
            'role' => 'string',
            'scope' => 'array',
        ], "$jsonPath.users[*]");

        $this->cantSeeResponseJsonMatchesJsonPath("$jsonPath.consumers");
    }

    public function assertConsumers($jsonPath = '$.data[*]', $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType([
            'client_id' => 'string',
            'client_secret' => 'string',
            'description' => 'string',
            'scope' => 'array',
            '_id' => 'string',
        ], $jsonPath);
    }

    public function assertCurrentUser($jsonPath = '$.data', $code = 200)
    {
        $this->seeResponseCodeIs($code);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'active' => 'boolean',
            'email' => 'string',
            'temporary_email' => 'string|null',
            'first_name' => 'string',
            'last_name' => 'string',
            'username' => 'string',
            'access_tokens' => 'array',
            'refresh_tokens' => 'array',
            'scope' => 'array',
        ], $jsonPath);
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

    public function loginConsumer($consumer)
    {
        $this->logout();
        $this->setHeader('Authorization',
            'Basic ' . base64_encode($consumer->client_id . ':' . $consumer->client_secret));
    }

    public function createConsumer()
    {
        $this->sendPOST('api/v1/projects/consumers',
            ['description' => $this->getFaker()->text('20'), 'scope' => ['read', 'check']]);

        $this->sendGET('api/v1/projects/consumers');

        return json_decode($this->grabResponse())->data[0];
    }

    public function getMongo()
    {
        if (!$this->mongo) {
            $this->mongo = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
        }

        return $this->mongo;
    }

    public function dropDatabase()
    {
        $this->getMongo()->executeCommand('gandalf_test', new MongoDB\Driver\Command(['dropDatabase' => 1]));
    }

    public function createProjectAndSetHeader(array $data = [])
    {
        $project = $this->createProject(false, $data);
        $this->setHeader('X-Application', $project->_id);

        return $project;
    }

    public function createProject($new = false, array $data = [])
    {
        if (!$this->project || $new) {
            $faker = $this->getFaker();
            $project = array_merge([
                'settings' => ['show_meta' => true],
                'title' => $faker->streetName,
                'description' => $faker->text('150'),
            ], $data);
            $this->sendPOST('api/v1/projects', $project);
            $project = json_decode($this->grabResponse());
            $this->assertProject('$.data', 201);
            $this->project = $project->data;
        }

        return $this->project;
    }

    public function createUser($new = false, $email = '', $verify = true)
    {
        $this->createAndLoginClient();
        if (!$this->user || $new) {
            $faker = $this->getFaker();

            $user_data = [
                'first_name' => $faker->firstName,
                'email' => ($email) ? $email : $faker->email,
                'password' => $this->getPassword(),
                'username' => $faker->firstName,
            ];

            $this->sendPOST('api/v1/users/', $user_data);
            $this->seeResponseCodeIs(201);
            $response = json_decode($this->grabResponse());
            $user_info = $response->data;

            if ($verify) {
                $this->sendPOST('api/v1/users/verify/email', ['token' => $response->sandbox->token_email->token]);
                $this->seeResponseCodeIs(200);
            }

            $this->sendPOST('api/v1/oauth/',
                [
                    'grant_type' => 'password',
                    'username' => $user_data['username'],
                    'password' => $user_data['password'],
                ]
            );

            $user_info->token = json_decode($this->grabResponse());
            $user_info->password = $user_data['password'];
            $user_info->sandbox = $response->sandbox;
            $this->user = $user_info;
        }

        return $this->user;
    }

    public function getPassword()
    {
        return $this->getFaker()->password() . '1aA';
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
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($client);
            $this->getMongo()->executeBulkWrite('gandalf_test.oauth_clients', $bulk);
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

    public function getCurrentClient()
    {
        return $this->client;
    }
}
