<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection
    |--------------------------------------------------------------------------
    |
    | The default queue connection used by the application.
    |
    */

    'default' => env(
        'QUEUE_CONNECTION',
        'database'
    ),



    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [


        /*
        |--------------------------------------------------------------------------
        | Sync Queue
        |--------------------------------------------------------------------------
        |
        | Executes jobs immediately.
        | Mainly used for testing.
        |
        */

        'sync' => [

            'driver' => 'sync',

        ],



        /*
        |--------------------------------------------------------------------------
        | Database Queue
        |--------------------------------------------------------------------------
        |
        | Stores jobs inside database table.
        |
        */

        'database' => [

            'driver' => 'database',

            'connection' => env(
                'DB_QUEUE_CONNECTION'
            ),

            'table' => env(
                'DB_QUEUE_TABLE',
                'jobs'
            ),

            'queue' => env(
                'DB_QUEUE',
                'default'
            ),

            'retry_after' => (int) env(
                'DB_QUEUE_RETRY_AFTER',
                90
            ),

            'after_commit' => false,

        ],



        /*
        |--------------------------------------------------------------------------
        | Redis Queue
        |--------------------------------------------------------------------------
        |
        | Recommended production queue driver.
        |
        */

        'redis' => [

            'driver' => 'redis',

            'connection' => env(
                'REDIS_QUEUE_CONNECTION',
                'default'
            ),

            'queue' => env(
                'REDIS_QUEUE',
                'default'
            ),

            'retry_after' => (int) env(
                'REDIS_QUEUE_RETRY_AFTER',
                90
            ),

            'block_for' => null,

            'after_commit' => false,

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | Store failed jobs for debugging and recovery.
    |
    */

    'failed' => [

        'driver' => env(
            'QUEUE_FAILED_DRIVER',
            'database-uuids'
        ),

        'database' => env(
            'DB_CONNECTION',
            'mysql'
        ),

        'table' => 'failed_jobs',

    ],

];