<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewScopes extends Migration
{/**
 * users' => [
 *
 * ],
 * 'consumers' => [
 * 'read' => 'decisions_view
 * 'check' => 'tables_query
 * ],
 */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $associated = [
            'create' => 'tables_create',
            'read' => 'tables_view',
            'update' => 'tables_update',
            'delete' => 'tables_delete',
            'check' => 'tables_query',
            'get_consumers' => 'consumers_get',
            'create_consumers' => 'consumers_manage',
            'update_consumers' => 'consumers_manage',
            'update_users' => 'users_manage',
            'add_user' => 'users_manage',
            'edit_project' => 'project_update',
            'delete_project' => 'project_delete',
            'delete_consumers' => 'consumers_manage',
            'delete_users' => 'users_manage',
        ];

        $consumers_associated = [
            'read' => 'decisions_view',
            'check' => 'tables_query',
        ];

        $apps = \DB::collection('applications')->get();
        foreach ($apps as $app) {
            $set = [];
            if ($app['users']) {
                $users = [];
                foreach ($app['users'] as $k => $user) {
                    $users[$k] = $user;
                    $users[$k]['scope'] = ['decisions_view'];
                    foreach ($user['scope'] as $scope) {
                        if (in_array($scope, array_keys($associated))) {
                            $users[$k]['scope'][] = $associated[$scope];
                        } else {
                            $users[$k]['scope'][] = $scope;
                        }
                    }
                    $users[$k]['scope'] = array_values(array_unique($users[$k]['scope']));
                }
                $set['users'] = $users;
            }
            if ($app['consumers']) {
                $consumers = [];
                foreach ($app['consumers'] as $k => $consumer)
                {
                    $consumers[$k] = $consumer;
                    $consumers[$k]['scope'] = [];
                    foreach ($consumer['scope'] as $scope) {
                        if (in_array($scope, array_keys($associated))) {
                            $consumers[$k]['scope'][] = $associated[$scope];
                        } else {
                            $consumers[$k]['scope'][] = $scope;
                        }
                    }
                }
                $set['consumers'] = $consumers;
            }
            if (!empty($set)) {
                \DB::collection('applications')->where('_id',
                    strval($app['_id']))->update(['$set' => $set]);
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
