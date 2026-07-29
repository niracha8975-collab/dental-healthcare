<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Main storage disk used by the application.
    |
    */

    'default' => env(
        'FILESYSTEM_DISK',
        'local'
    ),


    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [


        /*
        |--------------------------------------------------------------------------
        | Local Storage
        |--------------------------------------------------------------------------
        |
        | Private application files.
        |
        */

        'local' => [

            'driver' => 'local',

            'root' => storage_path(
                'app/private'
            ),

            'throw' => false,

        ],



        /*
        |--------------------------------------------------------------------------
        | Public Storage
        |--------------------------------------------------------------------------
        |
        | Files accessible through public/storage.
        |
        */

        'public' => [

            'driver' => 'local',

            'root' => storage_path(
                'app/public'
            ),

            'url' => env(
                'APP_URL'
            ) . '/storage',

            'visibility' => 'public',

            'throw' => false,

        ],



        /*
        |--------------------------------------------------------------------------
        | S3 Compatible Storage
        |--------------------------------------------------------------------------
        |
        | Future production cloud storage.
        |
        */

        's3' => [

            'driver' => 's3',

            'key' => env(
                'AWS_ACCESS_KEY_ID'
            ),

            'secret' => env(
                'AWS_SECRET_ACCESS_KEY'
            ),

            'region' => env(
                'AWS_DEFAULT_REGION'
            ),

            'bucket' => env(
                'AWS_BUCKET'
            ),

            'url' => env(
                'AWS_URL'
            ),

            'endpoint' => env(
                'AWS_ENDPOINT'
            ),

            'use_path_style_endpoint' => env(
                'AWS_USE_PATH_STYLE_ENDPOINT',
                false
            ),

            'throw' => false,

        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [

        public_path(
            'storage'
        ) => storage_path(
            'app/public'
        ),

    ],

];