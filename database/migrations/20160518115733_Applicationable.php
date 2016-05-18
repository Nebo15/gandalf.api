<?php

class Applicationable extends \Sokil\Mongo\Migrator\AbstractMigration
{
    public function up()
    {
        $user = $this->getCollection('users')->find()->where('username', 'admin')->findOne();
        if (!$user) {
            $this->getCollection('users')->insert([
                'username' => 'admin',
                'password' => '$2y$10$ur/AJm3FpWyCAAIEEcXQbebvMf0cUuT1dOKHC/.UNc9Z4MLe8mXJO',
                'email' => 'admin@admin.com',
            ]);
        }
        $user = $this->getCollection('users')->find()->where('username', 'admin')->findOne();

        $application = $this->getCollection('applications')->find()->where('title', 'migrated')->findOne();
        if (!$application) {
            $this->getCollection('applications')->insert([
                'title' => 'migrated',
                'description' => '',
                'users' => [
                    'user_id' => (string)$user->getId(),
                    'role' => 'admin',
                    'scope' => [
                        'create', 'read', 'update', 'delete', 'check'
                    ]
                ],
                'consumers' => []
            ]);
        }
        $application = $this->getCollection('applications')->find()->where('title', 'migrated')->findOne();
        $application_id = (string)$application->getId();
        $tables = $this->getCollection('tables')->find()->findAll();
        foreach ($tables as $table) {
            if (empty($table->applications)) {
                $table->applications = [$application_id];
                $table->save();
            }
        }

        $groups = $this->getCollection('groups')->find()->findAll();
        foreach ($groups as $group) {
            if (empty($group->applications)) {
                $group->applications = [$application_id];
                $group->save();
            }
        }
    }

    public function down()
    {

    }
}