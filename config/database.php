<?php

return [
    'default' => env('DB_CONNECTION', 'mongodb'),
    'connections' => [
        'testing' => [
            'driver' => 'mongodb',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 27017),
            'database' => env('DB_DATABASE', 'scoring_test'),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'options' => [],
        ],
        'mongodb' => [
            'driver' => 'mongodb',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 27017),
            'database' => env('DB_DATABASE', 'scoring'),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'options' => [],
        ],
    ],
    'migrations' => 'migrations',
];
