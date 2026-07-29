<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Domains that receive stateful API authentication cookies.
    |
    */

    'stateful' => explode(
        ',',
        env(
            'SANCTUM_STATEFUL_DOMAINS',
            sprintf(
                '%s%s',
                'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000',
                env('APP_URL') ? ',' . parse_url(
                    env('APP_URL'),
                    PHP_URL_HOST
                ) : ''
            )
        )
    ),



    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    */

    'guard' => [

        'web',

    ],



    /*
    |--------------------------------------------------------------------------
    | Token Expiration
    |--------------------------------------------------------------------------
    |
    | Token lifetime in minutes.
    |
    */

    'expiration' => env(
        'SANCTUM_EXPIRATION'
    ),



    /*
    |--------------------------------------------------------------------------
    | Token Prefix
    |--------------------------------------------------------------------------
    |
    | Helps identify application tokens.
    |
    */

    'token_prefix' => env(
        'SANCTUM_TOKEN_PREFIX',
        'dental_'
    ),



    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => [

        'authenticate_session' =>
            Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,

        'encrypt_cookies' =>
            Illuminate\Cookie\Middleware\EncryptCookies::class,

        'validate_csrf_token' =>
            Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,

    ],

];