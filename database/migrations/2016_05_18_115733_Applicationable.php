<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Applicationable extends Migration
{
    public function up()
    {
        $usersCollection = \DB::collection('users');
        $user = $usersCollection->where('username', 'admin')->first();
        if (!$user) {
            $usersCollection->insert([
                'username' => 'admin',
                'password' => '$2y$10$ur/AJm3FpWyCAAIEEcXQbebvMf0cUuT1dOKHC/.UNc9Z4MLe8mXJO',
                'email' => 'admin@admin.com',
            ]);
        }
        $user = $usersCollection->where('username', 'admin')->first();

        $applicationsCollection = \DB::collection('applications');
        $application = $applicationsCollection->where('title', 'migrated')->first();
        if (!$application) {
            $applicationsCollection->insert([
                'title' => 'migrated',
                'description' => '',
                'users' => [
                    [
                        'user_id' => (string)$user['_id'],
                        'role' => 'admin',
                        'scope' => [
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
                        ]
                    ]
                ],
                'consumers' => []
            ]);
        }
        $application = $applicationsCollection->where('title', 'migrated')->first();
        $application_id = (string)$application['_id'];

        \DB::collection('tables')
            ->where('applications', ['$exists' => false])
            ->update(
                ['$set' => ['applications' => [$application_id]]],
                ['multiple' => true]
            );

        \DB::collection('groups')
            ->where('applications', ['$exists' => false])
            ->update(
                ['$set' => ['applications' => [$application_id]]],
                ['multiple' => true]
            );
    }

    public function down()
    {

    }
}