<?php

return [
    'admin' => [
        'admin' => env('TOKEN_ADMIN_PW'),
    ],
    'consumer' => [
        'consumer' => env('TOKEN_CONSUMER_PW'),
        'bell' => env('TOKEN_BELL_PW'),
    ]
];