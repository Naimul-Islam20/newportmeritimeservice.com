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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'recaptcha' => [
        // Production keys from .env. On local, Google test keys are used automatically
        // (live keys only work on domains registered in reCAPTCHA admin).
        'site_key' => env('APP_ENV') === 'local' && ! filter_var(env('RECAPTCHA_USE_LIVE_KEYS', false), FILTER_VALIDATE_BOOL)
            ? '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
            : env('RECAPTCHA_SITE_KEY'),
        'secret' => env('APP_ENV') === 'local' && ! filter_var(env('RECAPTCHA_USE_LIVE_KEYS', false), FILTER_VALIDATE_BOOL)
            ? '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'
            : env('RECAPTCHA_SECRET_KEY'),
    ],

];
