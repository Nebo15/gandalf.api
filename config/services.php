<?php
return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN', ''),
        'sender' => env('POSTMARK_SENDER_EMAIL', ''),
        'templates' => [
            'welcome' => env('POSTMARK_TEMPLATE_WELCOME_ID', ''),
        ],
    ],
    'link' => [
        'confirmation_email' => env('LINK_CONFIRMATION_EMAIL', 'http://gandalf.dev/email/{code}'),
    ],
    'email' => [
        'enabled' => env('EMAIL_ENABLED', false)
    ]
];