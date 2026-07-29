<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    */

    'default' => env(
        'LOG_CHANNEL',
        'stack'
    ),


    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    */

    'deprecations' => [

        'channel' => env(
            'LOG_DEPRECATIONS_CHANNEL',
            'null'
        ),

        'trace' => env(
            'LOG_DEPRECATIONS_TRACE',
            false
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    */

    'channels' => [


        /*
        |--------------------------------------------------------------------------
        | Stack Channel
        |--------------------------------------------------------------------------
        */

        'stack' => [

            'driver' => 'stack',

            'channels' => [

                'daily',

            ],

            'ignore_exceptions' => false,

        ],



        /*
        |--------------------------------------------------------------------------
        | Daily Log Channel
        |--------------------------------------------------------------------------
        */

        'daily' => [

            'driver' => 'daily',

            'path' => storage_path(
                'logs/laravel.log'
            ),

            'level' => env(
                'LOG_LEVEL',
                'debug'
            ),

            'days' => env(
                'LOG_DAILY_DAYS',
                14
            ),

        ],



        /*
        |--------------------------------------------------------------------------
        | Single Log Channel
        |--------------------------------------------------------------------------
        */

        'single' => [

            'driver' => 'single',

            'path' => storage_path(
                'logs/laravel.log'
            ),

            'level' => env(
                'LOG_LEVEL',
                'debug'
            ),

        ],



        /*
        |--------------------------------------------------------------------------
        | Error Log Channel
        |--------------------------------------------------------------------------
        */

        'error' => [

            'driver' => 'monolog',

            'handler' => StreamHandler::class,

            'with' => [

                'stream' => storage_path(
                    'logs/error.log'
                ),

            ],

        ],



        /*
        |--------------------------------------------------------------------------
        | Null Channel
        |--------------------------------------------------------------------------
        */

        'null' => [

            'driver' => 'monolog',

            'handler' => NullHandler::class,

        ],


    ],

];