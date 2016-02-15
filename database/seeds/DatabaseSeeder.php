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
        \App\Models\Decision::truncate();
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

        \App\Models\Decision::create([
            'fields' => [
                [
                    "alias" => "borrowers_phone_name",
                    "title" => "Borrowers Phone Name",
                    "source" => "request",
                    "type" => "string",
                ],
                [
                    "alias" => "contact_person_phone_verification",
                    "title" => "Contact person phone verification",
                    "source" => "request",
                    "type" => "bool",
                ],
            ],
            'rules' => [
                [
                    'decision' => 'approve',
                    'description' => 'my',
                    'conditions' => [
                        [
                            'field_alias' => 'borrowers_phone_name',
                            'condition' => '$eq',
                            'value' => 'Vodaphone'

                        ],
                        [
                            'field_alias' => 'contact_person_phone_verification',
                            'condition' => '$eq',
                            'value' => 'true'
                        ],
                    ]
                ],
                [
                    'decision' => 'decline',
                    'description' => 'new',
                    'conditions' => [
                        [
                            'field_alias' => 'borrowers_phone_name',
                            'condition' => '$eq',
                            'value' => 'Life'

                        ],
                        [
                            'field_alias' => 'contact_person_phone_verification',
                            'condition' => '$eq',
                            'value' => 'true'
                        ],
                    ]
                ],
            ]
        ]);
    }
}
