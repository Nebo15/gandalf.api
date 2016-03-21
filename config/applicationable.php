<?php
return [
    'middleware' => ['oauth', 'applicationable.acl'],
    'routes' => [
        'prefix' => '/api/v1/',
        'applications' => '/projects',
        'consumers' => '/projects/consumers',
        'users' => '/projects/users',
    ],
    'scopes' => [
        'users' => [
            'create',
            'read',
            'update',
            'delete',
            'check',
        ],
        'consumers' => [
            'create',
            'check',
        ],
    ],
    'acl' => [
        'get' => [
            '~^\/api\/v1\/admin\/groups$~' => ['read'],
            '~^\/api\/v1\/admin\/groups\/(.+)$~' => ['read'],
            '~^\/api\/v1\/admin\/tables$~' => ['read'],
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['read'],
            '~^\/api\/v1\/admin\/decisions$~' => ['read'],
            '~^\/api\/v1\/admin\/decisions\/(.+)$~' => ['read'],
            '~^\/api\/v1\/decisions\/(.+)$~' => ['check'],
        ],
        'post' => [
            '~^\/api\/v1\/admin\/groups$~' => ['read', 'create'],
            '~^\/api\/v1\/projects\/users$~' => ['read', 'create'],
            '~^\/api\/v1\/admin\/groups\/(.+)\/copy$~' => ['read', 'create'],
            '~^\/api\/v1\/admin\/tables$~' => ['read', 'create'],
            '~^\/api\/v1\/admin\/tables\/(.+)\/copy$~' => ['read', 'create'],
            '~^\/api\/v1\/tables\/(.+)\/decisions$~' => ['check'],
            '~^\/api\/v1\/groups\/(.+)\/decisions$~' => ['check'],
        ],
        'put' => [
            '~^\/api\/v1\/admin\/groups\/(.+)$~' => ['read', 'update'],
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['read', 'update'],
        ],
        'delete' => [
            '~^\/api\/v1\/admin\/groups\/(.+)$~' => ['read', 'delete'],
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['read', 'delete'],
        ],
    ],
];