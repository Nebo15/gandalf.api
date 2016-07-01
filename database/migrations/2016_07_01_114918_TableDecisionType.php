<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableDecisionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::collection('tables')
            ->where('matching_type', 'scoring')
            ->update(
                ['$set' => ['decision_type' => 'numeric']],
                ['multiple' => true]
            );
        \DB::collection('tables')
            ->where('matching_type', 'decision')
            ->update(
                ['$set' => ['decision_type' => 'string']],
                ['multiple' => true]
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::collection('tables')
            ->update(
                ['$unset' => ['decision_type' => null]],
                ['multiple' => true]
            );
    }
}
