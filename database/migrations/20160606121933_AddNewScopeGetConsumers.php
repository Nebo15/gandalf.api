<?php

class AddNewScopeGetConsumers extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $this->getCollection('applications')->batchUpdate(
            [
                'users' => [
                    '$elemMatch' => [
                        'role' => 'admin',
                        'scope' => [
                            '$nin' => ['get_consumers']
                        ]
                    ]
                ]
            ],
            [
                '$push' => [
                    'users.$.scope' => 'get_consumers'
                ]
            ]
        );
    }
    
    public function down()
    {
        
    }
}