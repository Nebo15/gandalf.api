<?php
return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN', ''),
        'sender' => env('POSTMARK_SENDER_EMAIL', ''),
        'templates' => [
            'welcome' => env('POSTMARK_TEMPLATE_WELCOME_ID', ''),
            'reset_password' => env('POSTMARK_TEMPLATE_RESET_PASSWORD_ID', ''),
            'invite' => env('POSTMARK_TEMPLATE_INVITE_ID', ''),
        ],
    ],
    'link' => [
        'dump_project' => env('LINK_DUMP_PROJECT', 'http://gandalf.dev/dump'),
        'confirmation_email' => env('LINK_CONFIRMATION_EMAIL', 'http://gandalf.dev/email/{code}'),
        'reset_password' => env('LINK_RESET_PASSWORD', 'http://gandalf.dev/reset_password/{code}'),
        'invite' => env('LINK_INVITE', 'http://gandalf.dev/registration'),
        'admin_variant' => env('LINK_ADMIN_VARIANT', 'http://sandbox.gndf.io/#/tables/{table_id}/{variant_id}/edit'),
    ],
    'email' => [
        'enabled' => env('EMAIL_ENABLED', false),
    ],
    'status' => [
        'decisions_per_minute_link' => env('STATUS_DPM_LINK', 'http://status.gndf.io/api/v1/metrics/1/points'),
        'access_token' => env('STATUS_ACCESS_TOKEN', ''),
    ],
];