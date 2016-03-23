<?php

class DecisionsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function customer(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable();

        $decision = $I->checkDecision($I->getResponseFields()->data->_id);

        $I->loginConsumer($I->createConsumer());
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);

        $I->sendGET('api/v1/decisions/' . $decision->_id);
        $I->assertTableDecisionsForConsumer();
    }

    public function createFirst(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table_data = $I->createTable();
        $table_id_no_decisions = $table_data->_id;

        $table_data = $I->createTable();

        $table_id_with_decisions = $table_data->_id;
        $decision_table = $I->checkDecision($table_id_with_decisions);
        $I->assertEquals('Approve', $decision_table->final_decision);

        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_no_decisions);
        $I->seeResponseCodeIs(404);

        # filter by table_id
        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_with_decisions);
        $I->assertTableDecisionsForAdmin('first', '$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }

        $decision_data = $I->checkDecision($table_id_with_decisions, [
            'borrowers_phone_verification' => 'invalid',
            'contact_person_phone_verification' => 'invalid',
            'internal_credit_history' => 'invalid',
            'employment' => false,
            'property' => false,
        ]);
        $I->assertEquals($table_data->default_decision, $decision_data->final_decision);

        $I->sendGET('api/v1/admin/decisions');
        $I->assertTableDecisionsForAdmin('first', '$.data[*]');

        $decisions = $I->getResponseFields()->data;
        $I->assertEquals('invalid', $decisions[0]->request->borrowers_phone_verification);
        $I->assertEquals('Positive', $decisions[1]->request->borrowers_phone_verification);

        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }

        $I->loginConsumer($I->createConsumer());
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);
    }

    public function createAll(ApiTester $I)
    {

    }

    public function createGroup(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $group = $I->createGroup();

        $decision = $I->checkDecision($group->_id, [], 'first', 'groups');
        $I->assertTableDecisionsForConsumer();

        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->assertTableDecisionsForAdmin();
        $I->assertResponseDataFields([
            'group' => [
                '_id' => $group->_id,
                'title' => 'Group title',
                'description' => 'Group description'
            ]
        ]);
    }

    public function createInvalid(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table_id = $I->createTable()->_id;

        $I->sendPOST("api/v1/tables/$table_id/decisions", ['internal_credit_history' => 'okay']);
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType([
            'borrowers_phone_verification' => 'array',
            'contact_person_phone_verification' => 'array',
            'property' => 'array',
            'employment' => 'array',
        ], '$.data');

        $I->sendPOST("api/v1/tables/$table_id/decisions", [
            'internal_credit_history' => 'okay',
            'borrowers_phone_verification' => 'okay',
            'contact_person_phone_verification' => 'okay',
            'property' => 'okay',
            'employment' => 'okay',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseMatchesJsonType(['property' => 'array', 'employment' => 'array'], '$.data');
    }
}
