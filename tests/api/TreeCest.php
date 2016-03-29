<?php


class TreeCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function makeTreeDecision(ApiTester $I)
    {
        $I->loginAdmin();
        $tree = $I->createTree();


        $I->checkTreeDecision($tree->_id);
        $I->seeResponseCodeIs(200);
    }
}
