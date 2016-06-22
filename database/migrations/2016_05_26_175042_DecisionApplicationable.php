<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DecisionApplicationable extends Migration
{
    public function up()
    {
        $appColl = \DB::collection('applications');
        $appColl->where('users', [
            '$elemMatch' => [
                'role' => 'admin',
                'scope' => [
                    '$nin' => ['update_consumers']
                ]
            ]
        ])->update([
            '$push' => [
                'users.$.scope' => 'update_consumers'
            ]
        ]);

        $tables = [];
        foreach (\DB::collection('tables')->get() as $table) {
            $tables[(string)$table['_id']] = $table['applications'];
        }

        $bulk = new MongoDB\Driver\BulkWrite;
        $manager = new MongoDB\Driver\Manager(sprintf('mongodb://%s:%s', env('DB_HOST'), env('DB_PORT')));

        $skip = 0;
        $limit = 200;
        while ($decisions = \DB::collection('decisions')->limit($limit)->skip($skip)->get()) {
            if (!$decisions) {
                break;
            }
            foreach ($decisions as $decision) {
                $applications = [];
                if (array_key_exists('table_id', $decision)) {
                    $applications = (array_key_exists((string)$decision['table_id'], $tables))
                        ? $tables[(string)$decision['table_id']]
                        : [];
                } elseif (array_key_exists('table', $decision)) {
                    $applications = array_key_exists((string)$decision['table']['_id'], $tables)
                        ? $tables[(string)$decision['table']['_id']]
                        : [];
                }
                $bulk->update(
                    ['_id' => $decision['_id']],
                    ['$set' => ['applications' => $applications]]
                );
            }
            $skip += $limit;
            $manager->executeBulkWrite(env('DB_DATABASE') . '.decisions', $bulk);
        }
    }

    public function down()
    {

    }
}
