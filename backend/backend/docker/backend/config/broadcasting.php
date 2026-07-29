<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | Default connection used for broadcasting events.
    |
    */

    'default' => env(
        'BROADCAST_CONNECTION',
        'log'
    ),



    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [


        /*
        |--------------------------------------------------------------------------
        | Reverb WebSocket Server
        |--------------------------------------------------------------------------
        |
        | Laravel real-time communication server.
        |
        */

        'reverb' => [

            'driver' => 'reverb',

            'key' => env(
                'REVERB_APP_KEY'
            ),

            'secret' => env(
                'REVERB_APP_SECRET'
            ),

            'app_id' => env(
                'REVERB_APP_ID'
            ),

            'options' => [

                'host' => env(
                    'REVERB_HOST'
                ),

                'port' => env(
                    'REVERB_PORT',
                    8080
                ),

                'scheme' => env(
                    'REVERB_SCHEME',
                    'http'
                ),

            ],

        ],



        /*
        |--------------------------------------------------------------------------
        | Log Broadcast
        |--------------------------------------------------------------------------
        |
        | Development and testing.
        |
        */

        'log' => [

            'driver' => 'log',

        ],



        /*
        |--------------------------------------------------------------------------
        | Null Broadcast
        |--------------------------------------------------------------------------
        */

        'null' => [

            'driver' => 'null',

        ],

    ],

];