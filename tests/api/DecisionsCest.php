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

        $decision = $I->checkDecision($I->getResponseFields()->data->_id);

        $I->loginConsumer();
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);

        $I->sendGET('api/v1/decisions/' . $decision->_id );
        $I->assertTableDecisionsForConsumer();
    }
}
