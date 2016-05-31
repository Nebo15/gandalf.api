<?php

class MatchingType extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('tables')->batchUpdate(
            [
                'matching_type' => "first",
            ],
            [
                '$set' => [
                    'matching_type' => "decision",
                ],
            ]
        );

        $this->getCollection('tables')->batchUpdate(
            [
                'matching_type' => ['$exists' => false],
            ],
            [
                '$set' => [
                    'matching_type' => "decision",
                ],
            ]
        );

        $this->getCollection('tables')->batchUpdate(
            [
                'matching_type' => "all",
            ],
            [
                '$set' => [
                    'matching_type' => "scoring",
                ],
            ]
        );
    }

    public function down()
    {

    }
}