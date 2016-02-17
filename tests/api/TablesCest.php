<?php

class TablesCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function all(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertTable('$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/tables/' . $item->_id);
            $I->assertTable();
        }
    }

    public function update(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertTable('$.data[*]');

        $id = $I->getResponseFields()->data[0]->_id;
        $data = $I->getTableData();
        $data['title'] = 'Updated title';
        $I->sendPUT('api/v1/admin/tables/' . $id, ['table' => $data]);
        $I->assertTable();
        $I->assertResponseDataFields(['title' => $data['title']]);
    }

    public function delete(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();
        $I->createTable();

        $I->sendGET('api/v1/admin/tables');
        $I->assertTable('$.data[*]');

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
        $I->createTable();

        $I->checkDecision($I->getResponseFields()->data->_id);

        $I->sendGET('api/v1/admin/tables/decisions');
        $I->assertTableDecisionsForAdmin('$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/tables/' . $item->_id . '/decisions');
            $I->assertTableDecisionsForAdmin();
        }
    }
}
