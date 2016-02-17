<?php

class DecisionsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function customerDecisions(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();

        $I->checkDecision($I->getResponseFields()->data->_id);

        $I->loginConsumer();
        $I->sendGET('api/v1/admin/tables/decisions');
        $I->seeResponseCodeIs(401);

        $I->sendGET('api/v1/tables/decisions');
        $I->assertTableDecisionsForConsumer('$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/tables/' . $item->_id . '/decisions');
            $I->assertTableDecisionsForConsumer();
        }
    }
}
