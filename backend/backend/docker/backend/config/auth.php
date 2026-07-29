<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard and password broker used by application.
    |
    */

    'defaults' => [

        'guard' => env(
            'AUTH_GUARD',
            'web'
        ),

        'passwords' => env(
            'AUTH_PASSWORD_BROKER',
            'users'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define every authentication method.
    |
    */

    'guards' => [

        /*
        |--------------------------------------------------------------------------
        | Web Guard
        |--------------------------------------------------------------------------
        |
        | Session based authentication.
        |
        */

        'web' => [

            'driver' => 'session',

            'provider' => 'users',

        ],



        /*
        |--------------------------------------------------------------------------
        | API Guard
        |--------------------------------------------------------------------------
        |
        | Token authentication using Sanctum.
        |
        */

        'api' => [

            'driver' => 'sanctum',

            'provider' => 'users',

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        'users' => [

            'driver' => 'eloquent',

            'model' => App\Models\User::class,

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    */

    'passwords' => [

        'users' => [

            'provider' => 'users',

            'table' => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_reset_tokens'
            ),

            'expire' => 60,

            'throttle' => 60,

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];