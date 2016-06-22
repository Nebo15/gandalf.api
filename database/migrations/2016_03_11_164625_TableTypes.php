<?php

use Illuminate\Database\Migrations\Migration;

class TableTypes extends Migration
{
    public function up()
    {
        \DB::collection('tables')
            ->where('fields.type', 'bool')
            ->update(
                ['$set' => ['fields.type' => 'boolean']],
                ['multiple' => true]
            );
        \DB::collection('tables')
            ->where('fields.type', 'number')
            ->update(
                ['$set' => ['fields.type' => 'numeric']],
                ['multiple' => true]
            );
    }

    public function down()
    {
        \DB::collection('tables')
            ->where('fields.type', 'boolean')
            ->update(
                ['$set' => ['fields.type' => 'bool']],
                ['multiple' => true]
            );
        \DB::collection('tables')
            ->where('fields.type', 'numeric')
            ->update(
                ['$set' => ['fields.type' => 'number']],
                ['multiple' => true]
            );
    }
}
