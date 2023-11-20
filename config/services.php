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

    'iiko' => [
        'user' => env('IIKO_USER'),
        'secret' => env('IIKO_SECRET'),
        'organization' => env('IIKO_ORGANIZATION'),
    ],

    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID', '4e84eed1-58ee-4ba5-b3ff-c3b5a150ea20'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY', 'OWE3NDE2NTYtODllYS00MWRlLTkyNGMtNmFmZDczZTBjNDky')
    ],

];
