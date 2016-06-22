<?php

use Illuminate\Database\Migrations\Migration;

class AddGroupToDecisions extends Migration
{
    public function up()
    {
        \DB::collection('decisions')->update(
            ['$set' => ['group' => null]],
            ['multiple' => true]
        );
    }

    public function down()
    {
        \DB::collection('decisions')->update(
            ['$unset' => ['group' => null]],
            ['multiple' => true]
        );
    }
}
