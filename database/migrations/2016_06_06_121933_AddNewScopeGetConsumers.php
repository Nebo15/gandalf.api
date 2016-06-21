<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewScopeGetConsumers extends Migration
{
    public function up()
    {
        \DB::collection('applications')
            ->where('users', [
                '$elemMatch' => [
                    'role' => 'admin',
                    'scope' => [
                        '$nin' => ['get_consumers']
                    ]
                ]
            ])
            ->update(
                ['$push' => ['users.$.scope' => 'get_consumers']],
                ['multiple' => true]
            );
    }

    public function down()
    {

    }
}