<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Configuration for external integrations.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | MyPCU Healthcare Integration
    |--------------------------------------------------------------------------
    */

    'mypcu' => [

        'base_url' => env(
            'MYPCU_API_URL'
        ),

        'api_key' => env(
            'MYPCU_API_KEY'
        ),

        'secret' => env(
            'MYPCU_API_SECRET'
        ),

        'timeout' => env(
            'MYPCU_TIMEOUT',
            30
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging
    |--------------------------------------------------------------------------
    */

    'firebase' => [

        'project_id' => env(
            'FIREBASE_PROJECT_ID'
        ),

        'server_key' => env(
            'FIREBASE_SERVER_KEY'
        ),

        'credentials' => env(
            'FIREBASE_CREDENTIALS'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Mailgun
    |--------------------------------------------------------------------------
    */

    'mailgun' => [

        'domain' => env(
            'MAILGUN_DOMAIN'
        ),

        'secret' => env(
            'MAILGUN_SECRET'
        ),

        'endpoint' => env(
            'MAILGUN_ENDPOINT',
            'api.mailgun.net'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | AWS
    |--------------------------------------------------------------------------
    */

    'aws' => [

        'access_key_id' => env(
            'AWS_ACCESS_KEY_ID'
        ),

        'secret_access_key' => env(
            'AWS_SECRET_ACCESS_KEY'
        ),

        'default_region' => env(
            'AWS_DEFAULT_REGION',
            'ap-southeast-1'
        ),

        'bucket' => env(
            'AWS_BUCKET'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Google Services
    |--------------------------------------------------------------------------
    */

    'google' => [

        'client_id' => env(
            'GOOGLE_CLIENT_ID'
        ),

        'client_secret' => env(
            'GOOGLE_CLIENT_SECRET'
        ),

        'redirect' => env(
            'GOOGLE_REDIRECT_URI'
        ),

    ],


];