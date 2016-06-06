<?php

class ProjectSettingsShowMeta extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('applications')->batchUpdate(
            ['_id' => ['$exists' => true]],
            ['$set' => ['settings' => ['show_meta' => true]]]
        );
    }

    public function down()
    {
        $this->getCollection('applications')->batchUpdate(
            [],
            ['$unset' => ['settings' => null]]
        );
    }
}