<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Indexes extends Migration
{
    public function up()
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->index(['table_id']);
        });
    }

    public function down()
    {
        Schema::table('decisions', function (Blueprint $table) {
            $table->dropIndex(['table_id']);
        });
    }
}
