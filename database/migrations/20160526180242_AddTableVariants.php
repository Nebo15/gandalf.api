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

        $decisions = $this->getCollection('decisions');
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
    }
}