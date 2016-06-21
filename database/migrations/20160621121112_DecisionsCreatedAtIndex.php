<?php

class DecisionsCreatedAtIndex extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('decisions')->ensureIndex(['created_at' => 1]);
    }

    public function down()
    {
        $this->getCollection('decisions')->getMongoCollection()->deleteIndex(['created_at' => 1]);
    }
}
