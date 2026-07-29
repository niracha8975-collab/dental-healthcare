<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Configuration for external services used by
    | Dental Healthcare Management System.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging
    |--------------------------------------------------------------------------
    |
    | Used for mobile push notifications.
    |
    */

    'firebase' => [

        'project_id' => env(
            'FIREBASE_PROJECT_ID'
        ),

        'client_email' => env(
            'FIREBASE_CLIENT_EMAIL'
        ),

        'private_key' => env(
            'FIREBASE_PRIVATE_KEY'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | MyPCU Integration
    |--------------------------------------------------------------------------
    |
    | Healthcare data exchange service.
    |
    */

    'mypcu' => [

        'enabled' => env(
            'MYPCU_ENABLED',
            false
        ),

        'base_url' => env(
            'MYPCU_API_URL'
        ),

        'api_key' => env(
            'MYPCU_API_KEY'
        ),

        'timeout' => env(
            'MYPCU_TIMEOUT',
            30
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | OAuth Providers
    |--------------------------------------------------------------------------
    */

    'oauth' => [

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

    ],



    /*
    |--------------------------------------------------------------------------
    | Healthcare External Services
    |--------------------------------------------------------------------------
    */

    'healthcare' => [

        'api_url' => env(
            'HEALTHCARE_API_URL'
        ),

        'api_key' => env(
            'HEALTHCARE_API_KEY'
        ),

        'timeout' => env(
            'HEALTHCARE_API_TIMEOUT',
            30
        ),

    ],


];