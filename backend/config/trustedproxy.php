<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxy Addresses
    |--------------------------------------------------------------------------
    |
    | Proxies that Laravel should trust.
    |
    */

    'proxies' => env(
        'TRUSTED_PROXIES',
        '*'
    ),



    /*
    |--------------------------------------------------------------------------
    | Trusted Proxy Headers
    |--------------------------------------------------------------------------
    |
    | Headers used to detect original request information.
    |
    */

    'headers' => [

        'forwarded' => false,

        'x-forwarded-for' =>
            'X_FORWARDED_FOR',

        'x-forwarded-host' =>
            'X_FORWARDED_HOST',

        'x-forwarded-port' =>
            'X_FORWARDED_PORT',

        'x-forwarded-proto' =>
            'X_FORWARDED_PROTO',

        'x-forwarded-prefix' =>
            'X_FORWARDED_PREFIX',

    ],


];