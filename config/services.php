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
    
    
    'coinpayments' => [
        'private_key' => '4e65D484C9f94e7A9742419123C9b78234dC93d0b973Bbf7818C1b320B721920',
        'public_key' => '58c371c24ef7e999ac124cec903254104131d149bf49f8ac7241ab2a097535c5',
        'merchant_id' => '0b1a839aaac1cc5cfb7c320964ec1061',
        'ipn_secret' => 'ABNr5546',
    ],

];
