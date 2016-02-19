<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DecisionTable::truncate();
        \App\Models\ConditionType::truncate();

        $types = [
            '$eq' => 'Equal',
            '$ne' => 'Not equal',
            '$gt' => 'Greater than',
            '$gte' => 'Greater than or equal to',
            '$lt' => 'Less than',
            '$lte' => 'Less than or equal to',
            '$in' => 'Equal to value in array',
            '$nin' => 'Not equal to value in array',
        ];
        $data = [];
        foreach ($types as $key => $value) {
            $data[] = [
                'title' => $value,
                'source' => 'request',
                'condition' => $key
            ];
        }

        \App\Models\ConditionType::insert($data);

        $csv = array_map('str_getcsv', file(__DIR__ . '/decisions-tables.csv'));

        array_walk($csv, function (&$row) use ($csv) {
            $row = array_combine(
                array_map('trim', explode(';', $csv[0][0])),
                array_map('trim', explode(';', $row[0]))
            );
        });

        $fields = array_shift($csv);

        $data = [
            'default_decision' => 'approve',
            'fields' => [],
            'rules' => []
        ];

        unset($fields['Than']);
        foreach ($fields as $field) {
            $type = 'string';
            if(in_array($field, ['Employment', 'Property'])){
                $type = 'bool';
            }
            $data['fields'][] = [
                "key" => strtolower(str_replace(' ', '_', $field)),
                "title" => $field,
                "source" => "request",
                "type" => $type,
            ];
        }
        foreach ($csv as $rule) {
            $than = $rule['Than'];
            unset($rule['Than']);

            $conditions = [];
            foreach ($rule as $key => $value) {
                if ($value == 'y') {
                    $value = true;
                } elseif ($value == 'n') {
                    $value = false;
                }
                $conditions[] = [
                    'field_key' => $key,
                    'condition' => '$eq',
                    'value' => $value
                ];
            }
            $data['rules'][] = [
                'than' => $than,
                'description' => '',
                'conditions' => $conditions
            ];
        }

        \App\Models\DecisionTable::create($data);
    }
}
