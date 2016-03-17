<?php

class Indexes extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('decisions')->ensureIndex(['table_id' => 1]);
    }

    public function down()
    {
        $this->getCollection('decisions')->getMongoCollection()->deleteIndex(['table_id' => 1]);
    }
}