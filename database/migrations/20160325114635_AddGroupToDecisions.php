<?php

class AddGroupToDecisions extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('decisions')->update([], ['$set' => ['group' => null]]);
    }
    
    public function down()
    {
        $this->getCollection('decisions')->update([], ['$unset' => ['group' => null]]);
    }
}