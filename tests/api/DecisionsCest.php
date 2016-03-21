<?php

class DecisionsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function customerDecisions(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable();
        $table = $I->getResponseFields()->data->_id;

        $decision = $I->checkDecision($table);

        $I->loginConsumer($I->createConsumer());

        $I->checkDecision($table);

        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);

        $I->sendGET('api/v1/decisions/' . $decision->_id);
        $I->assertTableDecisionsForConsumer();
    }
}
