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
        'confirmation_email' => env('LINK_CONFIRMATION_EMAIL', 'http://gandalf.dev/email/{code}'),
        'reset_password' => env('LINK_RESET_PASSWORD', 'http://gandalf.dev/reset_password/{code}'),
        'invite' => env('LINK_INVITE', 'http://gandalf.dev/registration'),
    ],
    'email' => [
        'enabled' => env('EMAIL_ENABLED', false)
    ]
];