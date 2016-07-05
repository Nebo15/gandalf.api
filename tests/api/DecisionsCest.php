<?php

class DecisionsCest
{
    public function _before(ApiTester $I)
    {
        $I->dropDatabase();
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function customer(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable();

        $decision = $I->makeDecision($I->getResponseFields()->data->_id);

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
        $decision_table = $I->makeDecision($table_id_with_decisions);
        $I->assertEquals('Approve', $decision_table->final_decision);

        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_no_decisions);
        $I->seeResponseCodeIs(404);

        # filter by table_id
        $I->sendGET('api/v1/admin/decisions?table_id=' . $table_id_with_decisions);
        $I->assertTableDecisionsForAdmin('decision', '$.data[*]');
        foreach ($I->getResponseFields()->data as $item) {
            $I->sendGET('api/v1/admin/decisions/' . $item->_id);
            $I->assertTableDecisionsForAdmin();
        }
        $I->sendGET('api/v1/admin/decisions/123');
        $I->seeResponseCodeIs(404);

        $decision_data = $I->makeDecision($table_id_with_decisions, [
            'borrowers_phone_verification' => 'invalid',
            'contact_person_phone_verification' => 'invalid',
            'internal_credit_history' => 'invalid',
            'employment' => false,
            'property' => false,
        ]);
        $I->assertEquals($table_data->variants[0]->default_decision, $decision_data->final_decision);

        $I->sendGET('api/v1/admin/decisions');
        $I->assertTableDecisionsForAdmin('decision', '$.data[*]');

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
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table = $I->createTable($I->getShortTableDataMatchingTypeAll());
        $decisionsData = [
            # default decision
            ['points' => 15, 'request' => ['string' => 'Invalid', 'numeric' => 1, 'bool' => false]],
            # decision rule matched
            ['points' => 100, 'request' => ['string' => 'Yes', 'numeric' => 500, 'bool' => false]],
            # second and third rule matched
            ['points' => -25.5, 'request' => ['string' => 'Not', 'numeric' => 200, 'bool' => true]],
        ];
        foreach ($decisionsData as $item) {
            $I->makeDecision($table->_id, $item['request'], 'scoring');
            $I->assertResponseDataFields(['final_decision' => $item['points']]);
        }
    }

    public function checkDecisionAccess(ApiTester $I)
    {
        $user = $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $table = $I->createTable($I->getShortTableDataMatchingTypeAll());
        $decisions = ['points' => 15, 'request' => ['string' => 'Invalid', 'numeric' => 1, 'bool' => false]];
        $data = $I->makeDecision($table->_id, $decisions['request'], 'scoring');
        $I->sendGET('api/v1/admin/decisions');
        $I->assertContains($data->_id, $I->grabResponse());

        $second_user = $I->createUser(true);
        $I->loginUser($second_user);
        $I->createProject(true);
        $I->sendGET('api/v1/admin/decisions');
        $I->assertNotContains($data->_id, $I->grabResponse());

        $I->loginUser($user);
        $I->sendPOST('api/v1/projects/users',
            [
                'user_id' => $second_user->_id,
                'role' => 'manager',
                'scope' => ['tables_create', 'tables_view', 'tables_update', 'decisions_view'],
            ]);

        $I->loginUser($second_user);
        $I->sendGET('api/v1/admin/decisions');
        $I->assertContains($data->_id, $I->grabResponse());
    }

    public function checkManyVariants(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $tableData = $I->getTableShortData();
        $variantId1 = $I->getMongoId();
        $variantId2 = $I->getMongoId();
        $tableData['variants'][0]['_id'] = $variantId1;
        $tableData['variants'][] = [
            '_id' => $variantId2,
            'default_decision' => 'Decline',
            'rules' => [
                [
                    'than' => 'Approve',
                    'title' => 'Valid rule title',
                    'description' => 'Valid rule description',
                    'conditions' => [
                        [
                            'field_key' => 'numeric',
                            'condition' => '$eq',
                            'value' => true,
                        ],
                        [
                            'field_key' => 'string',
                            'condition' => '$eq',
                            'value' => 'Variant',
                        ],
                        [
                            'field_key' => 'bool',
                            'condition' => '$eq',
                            'value' => true,
                        ],
                    ],
                ],
            ],
        ];
        $table = $I->createTable($tableData);

        $checkData = [
            [
                'numeric' => 500,
                'string' => 'Yes',
                'bool' => false,
                'variant_id' => $variantId1,
            ],
            [
                'numeric' => 500,
                'string' => 'Variant',
                'bool' => true,
                'variant_id' => $variantId2,
            ],
        ];
        foreach ($checkData as $item) {
            $decision = $I->makeDecision($table->_id, $item);
            $I->assertTableDecisionsForConsumer();

            $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
            $I->assertTableDecisionsForAdmin();
            $I->assertResponseDataFields([
                'final_decision' => 'Approve',
                'table' => [
                    '_id' => $table->_id,
                    'variant' => [
                        '_id' => $item['variant_id'],
                    ],
                ],
            ]);
        }
        $decision = $I->makeDecision($table->_id, [
            'numeric' => 500,
            'string' => 'Yes',
            'bool' => false,
        ]);
        $I->assertTableDecisionsForConsumer();
        $I->sendGET('api/v1/admin/decisions/' . $decision->_id);
        $I->assertTableDecisionsForAdmin();
        $I->assertTrue(in_array($I->getResponseFields()->data->table->variant->_id, [$variantId1, $variantId2]));

        foreach ([$variantId1, $variantId2] as $variantId) {
            $I->sendGET('api/v1/admin/decisions?variant_id=' . $variantId);
            $I->seeResponseCodeIs(200);
            foreach ($I->getResponseFields()->data as $decision) {
                $I->assertTrue(
                    $decision->table->variant->_id == $variantId,
                    "Decision doesn't filtered by variant_id $variantId"
                );
            }
        }
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

    public function updateMetaOk(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable($I->getTableShortData());

        $decision = $I->makeDecision(
            $I->getResponseFields()->data->_id,
            ['bool' => true, 'numeric' => 123, 'string' => 'Yes']
        );

        $data = [
            'ok' => '0981723qweasdzxcTYUGHJBNBNM!@#$%^&*()_+{}|":>?<~`',
            'json' => '{"type":{"num":123}}',
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
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader();
        $I->createTable($I->getTableShortData());

        $decision = $I->makeDecision(
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

    public function hideMeta(ApiTester $I)
    {
        $I->createAndLoginUser();
        $I->createProjectAndSetHeader(['settings' => ['show_meta' => false]]);
        $I->createTable();

        $I->makeDecision($I->getResponseFields()->data->_id, [], 'decision', false);
    }
}
