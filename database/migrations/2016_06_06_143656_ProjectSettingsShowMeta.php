<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectSettingsShowMeta extends Migration
{
    public function up()
    {
        \DB::collection('applications')
            ->where('_id', ['$exists' => true])
            ->update(
                ['$set' => ['settings' => ['show_meta' => true]]],
                ['multiple' => true]
            );
    }

    public function down()
    {
        \DB::collection('applications')
            ->update(
                ['$unset' => ['settings' => null]],
                ['multiple' => true]
            );
    }
}
