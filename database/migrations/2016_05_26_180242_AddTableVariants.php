<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableVariants extends Migration
{
    public function up()
    {
        $indexedTables = [];
        foreach (\DB::collection('tables')->get() as $table) {
            $table['variants'] = [
                [
                    '_id' => new MongoDB\BSON\ObjectID,
                    'title' => $table['title'],
                    'description' => $table['description'],
                    'default_title' => $table['default_title'] ?? '',
                    'default_decision' => $table['default_decision'] ?? '',
                    'default_description' => $table['default_description'] ?? '',
                    'rules' => $table['rules'] ?? [],
                ]
            ];

            $id = strval($table['_id']);
            \DB::collection('tables')->where('_id', $id)->update([
                '$set' => [
                    'variants_probability' => '',
                    'variants' => $table['variants']
                ],
                '$unset' => [
                    'rules' => null,
                    'default_title' => null,
                    'default_decision' => null,
                    'default_description' => null,
                ]
            ]);
            $indexedTables[$id] = $table;
        }

        $decisionsToRemove = [];

        $bulk = new MongoDB\Driver\BulkWrite;
        $manager = new MongoDB\Driver\Manager(sprintf('mongodb://%s:%s', env('DB_HOST'), env('DB_PORT')));

        $skip = 0;
        $limit = 200;
        while ($decisions = \DB::collection('decisions')->limit($limit)->skip($skip)->get()) {
            if (!$decisions) {
                break;
            }
            foreach ($decisions as $decision) {
                $tableId = array_key_exists('table_id', $decision)
                    ? strval($decision['table_id'])
                    : (array_key_exists('table', $decision) ? strval($decision['table']['_id']) : null);

                if (!$tableId or !array_key_exists($tableId, $indexedTables)) {
                    $decisionsToRemove[] = $decision['_id'];
                } else {
                    $table = $indexedTables[$tableId];
                    $variant = $table['variants'][0];
                    $bulk->update(
                        ['_id' => $decision['_id']],
                        [
                            '$unset' => ['table_id' => null],
                            '$set' => [
                                'table' => [
                                    '_id' => $table['_id'],
                                    'title' => $table['title'],
                                    'description' => $table['description'],
                                    'matching_type' => $table['matching_type'] ?? 'first',
                                    'variant' => [
                                        '_id' => $variant['_id'],
                                        'title' => $variant['title'],
                                        'description' => $variant['description'],
                                    ]
                                ]
                            ]
                        ]
                    );
                }
            }
            $skip += $limit;
            $manager->executeBulkWrite(env('DB_DATABASE') . '.decisions', $bulk);
        }

        if ($decisionsToRemove) {
            \DB::collection('decisions')->where('_id', ['$in' => $decisionsToRemove])->delete();
        }
    }

    public function down()
    {
        $tables = \DB::collection('tables')->get();
        foreach ($tables as $table) {
            $variant = $table['variants'][0];
            \DB::collection('tables')->where('_id', strval($table['_id']))->update([
                '$set' => [
                    'rules' => $variant['rules'],
                    'default_title' => $variant['default_title'],
                    'default_decision' => $variant['default_decision'],
                    'default_description' => $variant['default_description'],
                ],
                '$unset' => [
                    'variants' => null,
                    'variants_probability' => null,
                ]
            ]);
        }
        \DB::collection('decisions')
            ->update(
                ['$unset' => ['table.variant' => null]],
                ['multiple' => true]
            );
    }
}
