<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUserIdIntoApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $apps = \DB::collection('applications')->get();
        foreach ($apps as $app) {
            if ($app['users']) {
                $users = [];
                foreach ($app['users'] as $k => $user) {
                    $users[$k] = $user;
                    $users[$k]['user_id'] = new \MongoDB\BSON\ObjectID($user['user_id']);
                }
                \DB::collection('applications')->where('_id', strval($app['_id']))->update(['$set' => ['users' => $users]]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
