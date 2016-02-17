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
    use _generated\ApiTesterActions;

    public function createTable()
    {
        $this->sendPOST('api/v1/admin/tables', ['table' => $this->getTableData()]);
        $this->assertTable('$.data', 201);
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
            'key' => 'string',
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
            'field_key' => 'string',
            'condition' => 'string',
            'value' => 'string',
        ], "$jsonPath.rules[*].conditions[*]");
    }

    public function assertTableDecisionsForAdmin($jsonPath = '$.data')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
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
            'field_key' => 'string',
            'condition' => 'string',
            'value' => 'string',
            'matched' => 'boolean',
        ], "$jsonPath.rules[*].conditions[*]");
    }

    public function assertTableDecisionsForConsumer($jsonPath = '$.data')
    {
        $this->seeResponseCodeIs(200);
        $this->seeResponseMatchesJsonType([
            '_id' => 'string',
            'final_decision' => 'string',
            'request' => 'array',
            'rules' => 'array',
        ], $jsonPath);

        $this->seeResponseMatchesJsonType([
            'decision' => 'string|null',
            'description' => 'string',
        ], "$jsonPath.rules[*]");
    }

    public function checkDecision($id, array $data = [])
    {
        $data = $data ?: ['borrowers_phone_name' => 'okay', 'contact_person_phone_verification' => 'true'];
        $this->sendPOST("api/v1/tables/$id/check", $data);
        $this->assertTableDecisionsForConsumer();
    }

    public function getTableData()
    {
        return [
            'title' => 'test title',
            'description' => 'test description',
            'default_decision' => 'approve',
            'fields' => [
                [
                    "key" => "borrowers_phone_name",
                    "title" => "Borrowers Phone Name",
                    "source" => "request",
                    "type" => "string",
                ],
                [
                    "key" => "contact_person_phone_verification",
                    "title" => "Contact person phone verification",
                    "source" => "request",
                    "type" => "bool",
                ],
            ],
            'rules' => [
                [
                    'than' => 'approve',
                    'description' => 'my',
                    'conditions' => [
                        [
                            'field_key' => 'borrowers_phone_name',
                            'condition' => '$eq',
                            'value' => 'Vodaphone'
                        ],
                        [
                            'field_key' => 'contact_person_phone_verification',
                            'condition' => '$eq',
                            'value' => 'true'
                        ],
                    ]
                ],
                [
                    'than' => 'decline',
                    'description' => 'new',
                    'conditions' => [
                        [
                            'field_key' => 'borrowers_phone_name',
                            'condition' => '$eq',
                            'value' => 'Life'

                        ],
                        [
                            'field_key' => 'contact_person_phone_verification',
                            'condition' => '$eq',
                            'value' => 'true'
                        ],
                    ]
                ],
            ]
        ];
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
        $this->haveHttpHeader('Authorization', 'admin:admin');
    }

    public function loginConsumer()
    {
        $this->haveHttpHeader('Authorization', 'consumer:consumer');
    }

    public function logout()
    {
        $this->haveHttpHeader('Authorization', null);
    }
}
