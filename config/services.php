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

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_NUMBER')
    ],

    'student_portal' => [
        'url' => env('STUDENT_PORTAL_URL', 'http://127.0.0.1:8000'),
        'key' => env('STUDENT_PORTAL_API_KEY', 'mock-portal-client'),
    ],

    'sagilepmt' => [
        'url' => env('SAGILEPMT_URL', 'http://localhost:8000'),
        'key' => env('SAGILEPMT_KEY'),
        'client_id' => env('SAGILEPMT_CLIENT_ID', 'mock-portal-client'),
    ],

];
