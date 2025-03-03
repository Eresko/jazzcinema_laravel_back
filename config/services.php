<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'custom' => [
        'jwt' => env('JWT_SECRET'),
    ],
    'api_ticket_soft' => env('EXPORT_API_TICKET_SOFT'),
    'api_old_back' => env('EXPORT_API_OLD_BACK'),
    'app_url' => env('APP_URL'),
    'ticket_soft_url' => env('APP_TICKET_SOFT'),
    'telegram_token' => env('TELEGRAM'),
    'telephone_ip' => [
        "url" =>env('TELEPHONE_IP_URL'),
        "token" =>env('TELEPHONE_IP_TOKEN')
        ]

];
