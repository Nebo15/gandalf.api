<?php

class Indexes extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('decision_histories')->ensureIndex(['table_id' => 1]);
    }

    public function down()
    {
        $this->getCollection('decision_histories')->getMongoCollection()->deleteIndex(['table_id' => 1]);
    }
}