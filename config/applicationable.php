<?php
return [
    'middleware' => ['oauth', 'applicationable.acl'],
    'user_model' => 'App\Models\User',
    'routes' => [
        'prefix' => '/api/v1/',
        'applications' => '/projects',
        'current_application' => '/projects/current',
        'consumers' => '/projects/consumers',
        'users' => '/projects/users',
        'set_admin' => '/projects/users/admin',
    ],
    'required_scopes' => [
        'users' => [
            'tables_view',
        ],
    ],
    'scopes' => [
        'users' => [
            'tables_create',
            'tables_view',
            'tables_update',
            'tables_delete',
            'tables_query',
            'consumers_get',
            'consumers_manage',
            'users_manage',
            'project_update',
            'delete_project',
            'decisions_view',
        ],
        'consumers' => [
            'decisions_view',
            'tables_query',
        ],
    ],
    'acl' => [
        'get' => [
            '~^\/api\/v1\/admin\/tables$~' => ['tables_view'],
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['tables_view'],

            '~^\/api\/v1\/admin\/decisions$~' => ['decisions_view'],
            '~^\/api\/v1\/admin\/decisions\/(.+)$~' => ['decisions_view'],

            '~^\/api\/v1\/admin\/changelog\/tables\/(.+)$~' => ['tables_view'],

            '~^\/api\/v1\/decisions\/(.+)$~' => ['decisions_view'],

            '~^\/api\/v1\/admin\/changelog\/(.+)$~' => ['tables_view'],
            '~^\/api\/v1\/admin\/changelog\/(.+)\/(.+)$~' => ['tables_view'],
            '~^\/api\/v1\/admin\/changelog\/(.+)\/(.+)\/diff$~' => ['tables_view'],

            '~^\/api\/v1\/projects\/consumers~' => ['consumers_get'],
            '~^\/api\/v1\/projects\/current$~' => ['tables_view'],
            '~^\/api\/v1\/projects\/users$~' => ['tables_view'],
        ],
        'post' => [
            '~^\/api\/v1\/admin\/tables$~' => ['tables_create'],
            '~^\/api\/v1\/admin\/tables\/(.+)\/copy$~' => ['tables_create'],
            '~^\/api\/v1\/admin\/changelog\/(.+)\/(.+)\/rollback\/(.+)$~' => ['tables_update'],
            '~^\/api\/v1\/tables\/(.+)\/decisions$~' => ['tables_query'],
            '~^\/api\/v1\/projects\/users$~' => ['users_manage'],
            '~^\/api\/v1\/projects\/consumers~' => ['consumers_manage'],
        ],
        'put' => [
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['tables_update'],
            '~^\/api\/v1\/projects\/(.+)$~' => ['project_update'],
            '~^\/api\/v1\/projects\/consumers~' => ['consumers_manage'],
            '~^\/api\/v1\/projects\/users$~' => ['users_manage'],
        ],
        'delete' => [
            '~^\/api\/v1\/projects\/users$~' => ['users_manage'],
            '~^\/api\/v1\/projects\/consumers~' => ['consumers_manage'],
            '~^\/api\/v1\/projects\/(.+)$~' => ['project_delete'],
            '~^\/api\/v1\/admin\/tables\/(.+)$~' => ['tables_delete'],
        ],
    ],
];
