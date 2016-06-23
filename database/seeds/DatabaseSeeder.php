<?php

use \MongoDB\BSON\ObjectID;
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
        $this->seedApplication();
        $this->seedTable();
    }

    private function seedApplication()
    {
        $appId = new ObjectID('5745cc5af70466a1098b456e');
        $userId = new ObjectID('576bf5f9ce3c0c02ee2d314e');

        \DB::collection('users')->insert([
            '_id' => $userId,
            'username' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gndf.io',
            'password' => '$2y$10$8SyDk0r9BYrqHxmG5j9zLOZx8a5fHt9HhsyLt38wtb/iwxAW1dE2q',
            'active' => true,
            "accessTokens" => [
                [
                    "access_token" => "40509021b25294415df089403a577c2519df5757",
                    "client_id" => "d82f82004384c8835454603277bed410",
                    "expires" => 2566697453,
                    "scope" => null,
                ]
            ],
            "refreshTokens" => [
                [
                    "refresh_token" => "fc02f2234b57f289aa88cff798f2e0459fc23708",
                    "client_id" => "d599f6666ccd1375d8b9063891b2baa3",
                    "expires" => 2567903453,
                    "scope" => null,
                ]
            ]
        ]);

        \DB::collection('applications')->insert([
            "_id" => new ObjectID("576bf5f9ce3c0c02ee2d314d"),
            "settings" => ["show_meta" => true],
            "title" => "My first application",
            "description" => "Sample application.",
            "users" => [
                [
                    "user_id" => $userId,
                    "role" => "admin",
                    "scope" => [
                        "create",
                        "read",
                        "update",
                        "delete",
                        "check",
                        "get_consumers",
                        "create_consumers",
                        "update_consumers",
                        "update_users",
                        "add_user",
                        "edit_project",
                        "delete_project",
                        "delete_consumers",
                        "delete_users"
                    ],
                ],
            ],
            "consumers" => [
                [
                    "client_id" => "demo",
                    "client_secret" => "demo",
                    "description" => "Demo consumer",
                    "scope" => [
                        "read",
                        "check"
                    ],
                ]
            ]
        ]);

        \DB::collection('oauth_clients')->insert([
            "_id" => $appId,
            "client_id" => "d82f82004384c8835454603277bed410",
            "client_secret" => "+0+~^db49+R9WX%sdS~-EsZrK!'uVe;H"
        ]);
    }

    private function seedTable()
    {
        \DB::collection('tables')->insert([
            "_id" => new ObjectID("5745ce96f70466a2098b457c"),
            "title" => "PSP Scoring",
            "description" => "Sample table for PSP transaction scoring.",
            "default_title" => "DEFAULT_SCORE",
            "default_description" => "No scoring rules was applied.",
            "applications" => [
                "5745cc5af70466a1098b456e"
            ],
            "default_decision" => 30,
            "matching_type" => "all",
            "fields" => [
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cc6bbf81c40804383771"),
                    "key" => "ip",
                    "type" => "string",
                    "title" => "IP Address",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745ccd1bf81c40804383772"),
                    "key" => "merchant_distance",
                    "type" => "numeric",
                    "title" => "Distance to merchant",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cce5bf81c40804383773"),
                    "key" => "country_code",
                    "type" => "string",
                    "title" => "Country Code",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cd0abf81c40804383775"),
                    "key" => "card_brand",
                    "type" => "string",
                    "title" => "Card Brand",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745ccfdbf81c40804383774"),
                    "key" => "card_bin",
                    "type" => "string",
                    "title" => "Card BIN",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cd94bf81c40804383778"),
                    "key" => "card_issuer",
                    "type" => "string",
                    "title" => "Card Issuer",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cd25bf81c40804383776"),
                    "key" => "turnover",
                    "type" => "numeric",
                    "title" => "Total Turnover",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cd3fbf81c40804383777"),
                    "key" => "turnover_month",
                    "type" => "numeric",
                    "title" => "Monthly Turnover",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745ce25bf81c4080438377b"),
                    "key" => "payments_count_day",
                    "type" => "numeric",
                    "title" => "Transactions Count (day)",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cdf2bf81c4080438377a"),
                    "key" => "turnover_ip",
                    "type" => "numeric",
                    "title" => "Turnover by IP",
                    "source" => "request"
                ],
                [
                    "preset" => null,
                    "_id" => new ObjectID("5745cdd6bf81c40804383779"),
                    "key" => "payment_amount",
                    "type" => "numeric",
                    "title" => "Transaction Amount",
                    "source" => "request"
                ]
            ],
            "rules" => [
                [
                    "_id" => new ObjectID("5745ce3dbf81c4080438377c"),
                    "than" => 10,
                    "title" => "Blacklist IP address",
                    "description" => "We don't trust this IP's",
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$nin',
                            "value" => "192.168.0.1, 127.0.0.1",
                            "_id" => new ObjectID("575818a7f70466d6648b45b9")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ba")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45bb")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45bc")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45bd")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45be")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45bf")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c0")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c1")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c2")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c3")
                        ]
                    ]
                ],
                [
                    "_id" => new ObjectID("5745cea3bf81c4080438377d"),
                    "than" => -20,
                    "title" => "Not in delivery zone",
                    "description" => "Transaction out of delivery zone is suspicious. Even more suspicious when transaction amount is big.",
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c4")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$gt',
                            "value" => "200",
                            "_id" => new ObjectID("575818a7f70466d6648b45c5")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c6")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c7")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c8")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45c9")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ca")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45cb")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45cc")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45cd")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$gt',
                            "value" => "500",
                            "_id" => new ObjectID("575818a7f70466d6648b45ce")
                        ]
                    ]
                ],
                [
                    "_id" => new ObjectID("5745cea4bf81c4080438377e"),
                    "than" => -100,
                    "title" => "Merchant accepts card only from England.",
                    "description" => null,
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45cf")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d0")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$ne',
                            "value" => "GB",
                            "_id" => new ObjectID("575818a7f70466d6648b45d1")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d2")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d3")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d4")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d5")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d6")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d7")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d8")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45d9")
                        ]
                    ]
                ],
                [
                    "_id" => new ObjectID("5745cea5bf81c4080438377f"),
                    "than" => -100,
                    "title" => "Merchant accepts only Visa and MasterCard.",
                    "description" => null,
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45da")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45db")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45dc")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$nin',
                            "value" => "visa, mastercard",
                            "_id" => new ObjectID("575818a7f70466d6648b45dd")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45de")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45df")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e0")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e1")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e2")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e3")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e4")
                        ]
                    ]
                ],
                [
                    "_id" => new ObjectID("5745cea6bf81c40804383780"),
                    "than" => 10,
                    "title" => "Trusted banks",
                    "description" => "We wan't bigger conversion rate for some issuing banks.",
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e5")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e6")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e7")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e8")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45e9")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$in',
                            "value" => "barclays, privatbank",
                            "_id" => new ObjectID("575818a7f70466d6648b45ea")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45eb")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ec")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ed")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ee")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45ef")
                        ]
                    ]
                ],
                [
                    "_id" => new ObjectID("5745d01bbf81c40804383781"),
                    "than" => 60,
                    "title" => "Turnover limits",
                    "description" => "We don't want transaction to exceed maximum payment amount for this merchant.",
                    "conditions" => [
                        [
                            "field_key" => "ip",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f0")
                        ],
                        [
                            "field_key" => "merchant_distance",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f1")
                        ],
                        [
                            "field_key" => "country_code",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f2")
                        ],
                        [
                            "field_key" => "card_brand",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f3")
                        ],
                        [
                            "field_key" => "card_bin",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f4")
                        ],
                        [
                            "field_key" => "card_issuer",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45f5")
                        ],
                        [
                            "field_key" => "turnover",
                            "condition" => '$lt',
                            "value" => "10000",
                            "_id" => new ObjectID("575818a7f70466d6648b45f6")
                        ],
                        [
                            "field_key" => "turnover_month",
                            "condition" => '$lt',
                            "value" => "1000",
                            "_id" => new ObjectID("575818a7f70466d6648b45f7")
                        ],
                        [
                            "field_key" => "payments_count_day",
                            "condition" => '$lt',
                            "value" => "3",
                            "_id" => new ObjectID("575818a7f70466d6648b45f8")
                        ],
                        [
                            "field_key" => "turnover_ip",
                            "condition" => '$lt',
                            "value" => "15000",
                            "_id" => new ObjectID("575818a7f70466d6648b45f9")
                        ],
                        [
                            "field_key" => "payment_amount",
                            "condition" => '$is_set',
                            "value" => true,
                            "_id" => new ObjectID("575818a7f70466d6648b45fa")
                        ]
                    ]
                ]
            ],
        ]);
    }
}
