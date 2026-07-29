<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Default cache storage used by the application.
    |
    */

    'default' => env(
        'CACHE_STORE',
        'database'
    ),


    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    */

    'stores' => [

        /*
        |--------------------------------------------------------------------------
        | Array Cache
        |--------------------------------------------------------------------------
        |
        | Used mainly for testing.
        |
        */

        'array' => [

            'driver' => 'array',

            'serialize' => false,

        ],


        /*
        |--------------------------------------------------------------------------
        | Database Cache
        |--------------------------------------------------------------------------
        */

        'database' => [

            'driver' => 'database',

            'table' => env(
                'CACHE_TABLE',
                'cache'
            ),

            'connection' => env(
                'DB_CONNECTION'
            ),

            'lock_connection' => env(
                'DB_CONNECTION'
            ),

        ],


        /*
        |--------------------------------------------------------------------------
        | File Cache
        |--------------------------------------------------------------------------
        */

        'file' => [

            'driver' => 'file',

            'path' => storage_path(
                'framework/cache/data'
            ),

            'lock_path' => storage_path(
                'framework/cache/data'
            ),

        ],


        /*
        |--------------------------------------------------------------------------
        | Redis Cache
        |--------------------------------------------------------------------------
        |
        | High performance cache for production.
        |
        */

        'redis' => [

            'driver' => 'redis',

            'connection' => env(
                'REDIS_CACHE_CONNECTION',
                'cache'
            ),

            'lock_connection' => env(
                'REDIS_CACHE_CONNECTION',
                'cache'
            ),

        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | Prevents conflicts when multiple applications
    | share the same cache server.
    |
    */

    'prefix' => env(
        'CACHE_PREFIX',
        Str::slug(
            env(
                'APP_NAME',
                'laravel'
            ),
            '_'
        ) . '_cache'
    ),


];