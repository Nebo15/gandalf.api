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

    use _generated\ApiTesterActions;

    public function createTable(array $data = null)
    {
        $this->sendPOST('api/v1/admin/tables', ['table' => $data ?: $this->getTableData()]);
        $this->assertTable('$.data', 201);

        return $this->getResponseFields()->data;
    }

    public function assertListTable($jsonPath = '$.data[*]')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'default_decision' => 'string',
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
            'default_decision' => 'string',
            'rules' => 'array',
            'fields' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'key' => 'string:regex(@^[a-z_]+$@)',
            'title' => 'string',
            'source' => 'string',
            'type' => 'string',
        ], "$jsonPath.fields[*]");

        $this->seeResponseMatchesJsonType([
            'than' => 'string',
            'description' => 'string',
            'conditions' => 'array',
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            'field_alias' => 'string',
            'condition' => 'string',
            'value' => 'string|boolean',
        ], "$jsonPath.rules[*].conditions[*]");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions[*].matched");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions[*]._id]");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*]._id]");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.fields[*]._id]");
    }

    public function assertTableDecisionsForAdmin($jsonPath = '$.data')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'table_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'default_decision' => 'string',
            'final_decision' => 'string',
            'updated_at' => 'string',
            'created_at' => 'string',
            'rules' => 'array',
            'fields' => 'array',
            'request' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'key' => 'string',
            'title' => 'string',
            'source' => 'string',
            'type' => 'string',
        ], "$jsonPath.fields[*]");

        $this->seeResponseMatchesJsonType([
            'than' => 'string',
            'decision' => 'string|null',
            'description' => 'string',
            'conditions' => 'array',
        ], "$jsonPath.rules[*]");

        $this->seeResponseMatchesJsonType([
            'field_alias' => 'string',
            'condition' => 'string',
            'value' => 'string|boolean',
            'matched' => 'boolean',
        ], "$jsonPath.rules[*].conditions[*]");
    }

    public function assertTableDecisionsForConsumer($jsonPath = '$.data')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'table_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'final_decision' => 'string',
            'request' => 'array',
            'rules' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'decision' => 'string|null',
            'description' => 'string',
        ], "$jsonPath.rules[*]");

        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.fields");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.default_decision");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].than");
        $this->dontSeeResponseJsonMatchesJsonPath("$jsonPath.rules[*].conditions");
    }

    public function checkDecision($id, array $data = [])
    {
        $data = $data ?: [
            'borrowers_phone_verification' => 'Positive',
            'contact_person_phone_verification' => 'Positive',
            'internal_credit_history' => 'Positive',
            'employment' => true,
            'property' => true,
        ];
        $this->sendPOST("api/v1/tables/$id/check", $data);
        $this->assertTableDecisionsForConsumer();

        return $this->getResponseFields()->data;
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
            'description' => 'Test description',
            'fields' => [],
            'rules' => []
        ];

        unset($fields['Than']);
        foreach ($fields as $field) {
            $type = 'string';
            if (in_array($field, ['Employment', 'Property'])) {
                $type = 'bool';
            }
            $key = strtolower(str_replace(' ', '_', $field));
            $data['fields'][] = [
                "key" => $key,
                "title" => $field,
                "alias" => $key,
                "source" => "request",
                "type" => $type,
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
                    'field_alias' => strtolower(str_replace(' ', '_', $key)),
                    'condition' => '$eq',
                    'value' => $value
                ];
            }
            $data['rules'][] = [
                'than' => $than,
                'description' => '',
                'conditions' => $conditions
            ];
        }

        return $data;
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

    public function loginConsumer()
    {
        $this->amHttpAuthenticated('consumer', 'consumer');
    }

    public function logout()
    {
        $this->amHttpAuthenticated(null, null);
    }
}
