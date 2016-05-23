<?php

class Scopes extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $apps = $this->getCollection('applications')->find()->findAll();
        foreach ($apps as $key => $app) {
            if ($app->users) {
                $users = [];
                foreach ($app->users as $k => $user) {
                    $users[$k] = $user;
                    if ($user['role'] == 'admin') {
                        $users[$k]['scope'] =[
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
                $app->users = $users;
                $app->save();
            }
        }
    }

    public function down()
    {

    }
}