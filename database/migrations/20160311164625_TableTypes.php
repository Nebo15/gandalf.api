<?php

class TableTypes extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        do {
            $result = $this->getCollection('tables')
                ->createBatchUpdate()
                ->update(
                    ['fields.type' => 'bool'],
                    ['$set' => ['fields.$.type' => 'boolean']],
                    true
                )
                ->update(
                    ['fields.type' => 'number'],
                    ['$set' => ['fields.$.type' => 'numeric']],
                    true
                )
                ->execute()
                ->getResult();

        } while ($result['nModified'] != 0);
    }

    public function down()
    {

    }
}


