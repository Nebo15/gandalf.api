<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DecisionsCreatedAtIndex extends Migration
{
    public function up()
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
}
