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

        $id = $I->getResponseFields()->data[0]->_id;
        $I->sendGET($this->api_prefix . "/$id");
        $I->assertGroup();
    }

    public function createInvalid(ApiTester $I)
    {
        $I->sendPOST($this->api_prefix, []);
        $I->seeResponseCodeIs(401);

        $I->loginAdmin();

        $invalid_data = [
            ['probability' => 'random'],
            ['tables' => ['_id' => 'test']],
            ['tables' => ['_id' => 'test'], 'probability' => 'random'],
            ['tables' => [['_id' => 'test']], 'probability' => 'invalid'],
            ['tables' => [['invalid' => 'test']], 'probability' => 'random'],
        ];
        foreach ($invalid_data as $data) {
            $I->sendPOST($this->api_prefix, $data);
            $I->seeResponseCodeIs(422);
        }
    }

    public function updateOk(ApiTester $I)
    {
        $I->loginAdmin();
        $id = $I->createGroup()->_id;
        $table_id = strval(new MongoId);
        $I->sendPUT($this->api_prefix . "/$id", [
            'tables' => [['_id' => $table_id]],
            'invalid_field' => 'test'
        ]);

        $I->assertResponseDataFields([
            'tables' => [['_id' => $table_id]]
        ]);
        $I->dontSeeResponseJsonMatchesJsonPath("$.data.invalid_field");
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

        $invalid_data = [
            ['probability' => 'invalid'],
            ['tables' => ['_id' => 'test']],
            ['tables' => [['_id' => 'test']], 'probability' => 'invalid'],
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
        $I->sendPOST($this->api_prefix . "/$id/clone", []);
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
