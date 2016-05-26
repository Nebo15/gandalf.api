<?php

class AddTableVariants extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $decisions = $this->getCollection('decisions');
        $groups = $this->getCollection('groups');
        $tables = $this->getCollection('tables');
    }
    
    public function down()
    {
        
    }
}