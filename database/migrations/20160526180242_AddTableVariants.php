<?php

class AddTableVariants extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $tables = $this->getCollection('tables')->find()->findAll();
        $indexedTables = [];
        foreach ($tables as $table) {
            $table->variants = [
                [
                    '_id' => new MongoId,
                    'title' => $table->title,
                    'description' => $table->description,
                    'matching_type' => $table->matching_type,
                    'default_title' => $table->default_title,
                    'default_decision' => $table->default_decision,
                    'default_description' => $table->default_description,
                    'rules' => $table->rules
                ]
            ];
            $table->variants_probability = '';
            unset($table->rules);
            unset($table->matching_type);
            unset($table->default_title);

            unset($table->default_decision);
            unset($table->default_description);

            $table->save();
            $indexedTables[strval($table->_id)] = $table;
        }

        $decisionsToRemove = [];
        $collection = (new \MongoClient())->selectDB($this->getDatabase()->getName())->selectCollection('decisions');
        $decisions = $collection->find([], ['table', 'table_id']);
        $batchUpdate = (new \MongoUpdateBatch($collection));
        foreach ($decisions as $decision) {
            $tableId = array_key_exists('table_id', $decision)
                ? strval($decision['table_id'])
                : (array_key_exists('table', $decision) ? strval($decision['table']['_id']) : null);

            if (!$tableId or !array_key_exists($tableId, $indexedTables)) {
                $decisionsToRemove[] = $decision['_id'];
            } else {
                $table = $indexedTables[$tableId];
                $variant = $table->variants[0];
                $batchUpdate->add([
                    'q' => ['_id' => $decision['_id']],
                    'u' => [
                        '$unset' => ['table_id' => null],
                        '$set' => [
                            'table' => [
                                '_id' => $table->_id,
                                'title' => $table->title,
                                'description' => $table->description,
                                'matching_type' => $table->matching_type,
                                'variant' => [
                                    '_id' => $variant['_id'],
                                    'title' => $variant['title'],
                                    'description' => $variant['description'],
                                ]
                            ]
                        ]
                    ]
                ]);
            }
        }
        $batchUpdate->execute();
        if ($decisionsToRemove) {

        }
    }

    public function down()
    {
        $tables = $this->getCollection('tables')->find()->findAll();
        foreach ($tables as $table) {
            $variant = $table->variants[0];
            $table->rules = $variant['rules'];
            $table->default_title = $variant['default_title'];
            $table->matching_type = $variant['matching_type'];
            $table->default_decision = $variant['default_decision'];
            $table->default_description = $variant['default_description'];
            unset($table->variants);
            unset($table->variants_probability);
            $table->save();
        }

        $this->getCollection('decisions')->update([],
            ['$unset' => ['table.variant' => null]],
            ['multiple' => true]);
    }
}
