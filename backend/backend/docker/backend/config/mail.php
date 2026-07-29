<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Default transport used for sending emails.
    |
    */

    'default' => env(
        'MAIL_MAILER',
        'log'
    ),


    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */

    'mailers' => [


        /*
        |--------------------------------------------------------------------------
        | SMTP Mailer
        |--------------------------------------------------------------------------
        */

        'smtp' => [

            'transport' => 'smtp',

            'scheme' => env(
                'MAIL_SCHEME'
            ),

            'url' => env(
                'MAIL_URL'
            ),

            'host' => env(
                'MAIL_HOST',
                '127.0.0.1'
            ),

            'port' => env(
                'MAIL_PORT',
                2525
            ),

            'username' => env(
                'MAIL_USERNAME'
            ),

            'password' => env(
                'MAIL_PASSWORD'
            ),

            'timeout' => null,

            'local_domain' => env(
                'MAIL_EHLO_DOMAIN'
            ),

        ],



        /*
        |--------------------------------------------------------------------------
        | Log Mailer
        |--------------------------------------------------------------------------
        |
        | Development and testing mail driver.
        |
        */

        'log' => [

            'transport' => 'log',

            'channel' => env(
                'MAIL_LOG_CHANNEL'
            ),

        ],



        /*
        |--------------------------------------------------------------------------
        | Array Mailer
        |--------------------------------------------------------------------------
        |
        | Used for automated testing.
        |
        */

        'array' => [

            'transport' => 'array',

        ],

    ],



    /*
    |--------------------------------------------------------------------------
    | Global From Address
    |--------------------------------------------------------------------------
    */

    'from' => [

        'address' => env(
            'MAIL_FROM_ADDRESS',
            'hello@example.com'
        ),

        'name' => env(
            'MAIL_FROM_NAME',
            'Laravel'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    */

    'markdown' => [

        'theme' => 'default',

        'paths' => [

            resource_path(
                'views/vendor/mail'
            ),

        ],

    ],

];