<?php

class DecisionsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function customer(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable();

        $decision = $I->checkDecision($I->getResponseFields()->data->_id);

        $I->loginConsumer();
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);

        $I->sendGET('api/v1/decisions/' . $decision->_id);
        $I->assertTableDecisionsForConsumer();
    }

    public function createFirst(ApiTester $I)
    {
        $I->loginAdmin();
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

        $I->loginConsumer();
        $I->sendGET('api/v1/admin/decisions');
        $I->seeResponseCodeIs(401);
    }

    public function createAll(ApiTester $I)
    {
        $I->loginAdmin();
        $tableData = $I->getShortTableDataMatchingTypeAll();
        $table = $I->createTable($tableData);
        $decisionsData = [
            # default decision
            ['points' => 15, 'request' => ['string' => 'Invalid', 'numeric' => 1, 'bool' => false]],
            # first rule matched
            ['points' => 100, 'request' => ['string' => 'Yes', 'numeric' => 500, 'bool' => false]],
            # second and third rule matched
            ['points' => -25.5, 'request' => ['string' => 'Not', 'numeric' => 200, 'bool' => true]],
        ];
        foreach ($decisionsData as $item) {
            $I->checkDecision($table->_id, $item['request'], 'all');
            $I->assertResponseDataFields(['final_decision' => $item['points']]);
        }
    }

    public function createGroup(ApiTester $I)
    {
        $I->loginAdmin();
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
        $I->loginAdmin();
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

    public function updateMetaOk(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable($I->getTableShortData());

        $decision = $I->checkDecision(
            $I->getResponseFields()->data->_id,
            ['bool' => true, 'numeric' => 123, 'string' => 'Yes']
        );

        $data = [
            'ok' => '0981723qweasdzxcTYUGHJBNBNM!@#$%^&*()_+{}|":>?<~`',
            'json' => '{"type":{"num":123}}'
        ];
        $I->sendPUT("api/v1/admin/decisions/{$decision->_id}/meta", ['meta' => $data]);
        $I->assertTableDecisionsForAdmin();
        $I->assertResponseDataFields(['meta' => $data]);

        $data = [
            'updated' => '0981723qweasdzxcTYUGHJBNBNM!@#$%^&*()_+{}|":>?<~`',
        ];
        $I->sendPUT("api/v1/admin/decisions/{$decision->_id}/meta", ['meta' => $data]);
        $I->assertTableDecisionsForAdmin();
        $I->assertResponseDataFields(['meta' => $data]);
        $I->cantSeeResponseJsonMatchesJsonPath('$.data.meta.ok');
        $I->cantSeeResponseJsonMatchesJsonPath('$.data.meta.json');
    }

    public function updateMetaInvalid(ApiTester $I)
    {
        $I->loginAdmin();
        $I->createTable($I->getTableShortData());

        $decision = $I->checkDecision(
            $I->getResponseFields()->data->_id,
            ['bool' => true, 'numeric' => 123, 'string' => 'Yes']
        );

        $data = array_fill(0, 20, 'test');
        $data[str_repeat('2', 101)] = 'ok';
        $data['invalid#'] = 'ok';
        $data['stringLength'] = str_repeat('1', 501);
        $data['array'] = [];
        $data[] = [['array']];

        $I->sendPUT("api/v1/admin/decisions/{$decision->_id}/meta", ['meta' => $data]);
        $I->seeResponseCodeIs(422);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.meta_keys_amount');
        $I->canSeeResponseJsonMatchesJsonPath('$.data.key_20');
        $I->canSeeResponseJsonMatchesJsonPath('$.data.key_21');
        $I->canSeeResponseJsonMatchesJsonPath('$.data.key_22_value');
        $I->canSeeResponseJsonMatchesJsonPath('$.data.key_23_value');
        $I->canSeeResponseJsonMatchesJsonPath('$.data.key_24_value');

        $I->sendPUT("api/v1/admin/decisions/{$decision->_id}/meta", $data);
        $I->seeResponseCodeIs(422);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.meta');

        $I->sendPUT("api/v1/admin/decisions/{$decision->_id}/meta", []);
        $I->seeResponseCodeIs(422);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.meta');
    }
}
