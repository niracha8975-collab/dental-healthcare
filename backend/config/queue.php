<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection
    |--------------------------------------------------------------------------
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
        | Execute immediately.
        |
        */

        'sync' => [

            'driver' => 'sync',

        ],



        /*
        |--------------------------------------------------------------------------
        | Database Queue
        |--------------------------------------------------------------------------
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

            'block_for' => 3,

            'after_commit' => false,

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Batch Processing
    |--------------------------------------------------------------------------
    */

    'batching' => [

        'database' => env(
            'DB_CONNECTION',
            'mysql'
        ),

        'table' => 'job_batches',

    ],



    /*
    |--------------------------------------------------------------------------
    | Failed Job Storage
    |--------------------------------------------------------------------------
    */

    'failed' => [

        'driver' => 'database-uuids',

        'database' => env(
            'DB_CONNECTION',
            'mysql'
        ),

        'table' => 'failed_jobs',

    ],

];