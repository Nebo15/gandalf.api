<?php

class DecisionApplicationable extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('applications')->batchUpdate(
            [
                'users' => [
                    '$elemMatch' => [
                        'role' => 'admin',
                        'scope' => [
                            '$nin' => ['update_consumers']
                        ]
                    ]
                ]
            ],
            [
                '$push' => [
                    'users.$.scope' => 'update_consumers'
                ]
            ]
        );

        $tablesCollection = $this->getCollection('tables')->find()->findAll();
        $tables = [];
        foreach ($tablesCollection as $table) {
            $tables[(string)$table->getid()] = $table->applications;
        }

        $decisions_collection = (new \MongoClient())->selectDB($this->getDatabase()->getName())
            ->selectCollection('decisions');
        $decisions = $decisions_collection->find([], ['table', 'table_id']);
        $batchUpdate = (new \MongoUpdateBatch($decisions_collection));
        foreach ($decisions as $decision) {
            $applications = [];
            if (array_key_exists('table_id', $decision)) {
                $applications = (array_key_exists((string)$decision['table_id'], $tables)) ? $tables[(string)$decision['table_id']] : [];
            } elseif (array_key_exists('table', $decision)) {
                $applications = array_key_exists((string)$decision['table']['_id'], $tables) ? $tables[(string)$decision['table']['_id']] : [];
            }
            $batchUpdate->add(
                [
                    'q' => ['_id' => $decision['_id']],
                    'u' => ['$set' => ['applications' => $applications]]
                ]
            );
        }

        $batchUpdate->execute();
    }
    
    public function down()
    {
        
    }
}