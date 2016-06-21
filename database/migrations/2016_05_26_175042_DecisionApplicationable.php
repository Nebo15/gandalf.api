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

        $collection = (new \MongoClient())->selectDB(env('DB_DATABASE'))->selectCollection('decisions');
        $decisions = $collection->find([], ['table', 'table_id']);
        $batchUpdate = (new \MongoUpdateBatch($collection));
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

            $batchUpdate->add([
                'q' => ['_id' => $decision['_id']],
                'u' => ['$set' => ['applications' => $applications]]
            ]);
        }
        $batchUpdate->execute();
    }

    public function down()
    {

    }
}