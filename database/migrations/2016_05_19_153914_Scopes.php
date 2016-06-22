<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Scopes extends Migration
{
    public function up()
    {
        $apps = \DB::collection('applications')->get();
        foreach ($apps as $app) {
            if ($app['users']) {
                $users = [];
                foreach ($app['users'] as $k => $user) {
                    $users[$k] = $user;
                    if ($user['role'] == 'admin') {
                        $users[$k]['scope'] = [
                            'create',
                            'read',
                            'update',
                            'delete',
                            'check',
                            'create_consumers',
                            'delete_consumers',
                            'update_users',
                            'add_user',
                            'edit_project',
                            'delete_project',
                            'delete_consumers',
                            'delete_users',
                        ];
                    }
                }
                \DB::collection('applications')->where('_id', strval($app['_id']))->update(['$set' => ['users' => $users]]);
            }
        }
    }

    public function down()
    {

    }
}
