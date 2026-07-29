<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Default cache driver used by application.
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
        | Testing cache driver.
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

            'connection' => env(
                'DB_CACHE_CONNECTION'
            ),

            'table' => env(
                'DB_CACHE_TABLE',
                'cache'
            ),

            'lock_connection' => env(
                'DB_CACHE_LOCK_CONNECTION'
            ),

            'lock_table' => env(
                'DB_CACHE_LOCK_TABLE'
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
        */

        'redis' => [

            'driver' => 'redis',

            'connection' => env(
                'REDIS_CACHE_CONNECTION',
                'default'
            ),

            'lock_connection' => env(
                'REDIS_CACHE_LOCK_CONNECTION',
                'default'
            ),

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
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