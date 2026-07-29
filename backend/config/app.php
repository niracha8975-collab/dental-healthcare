<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env(
        'APP_NAME',
        'Dental Healthcare'
    ),



    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    */

    'env' => env(
        'APP_ENV',
        'production'
    ),



    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env(
        'APP_DEBUG',
        false
    ),



    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env(
        'APP_URL',
        'http://localhost'
    ),



    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => env(
        'APP_TIMEZONE',
        'Asia/Bangkok'
    ),



    /*
    |--------------------------------------------------------------------------
    | Application Locale
    |--------------------------------------------------------------------------
    */

    'locale' => env(
        'APP_LOCALE',
        'th'
    ),



    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    */

    'fallback_locale' => env(
        'APP_FALLBACK_LOCALE',
        'en'
    ),



    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    */

    'faker_locale' => env(
        'APP_FAKER_LOCALE',
        'th_TH'
    ),



    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',



    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    */

    'maintenance' => [

        'driver' => env(
            'APP_MAINTENANCE_DRIVER',
            'file'
        ),

    ],



    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        Illuminate\Auth\AuthServiceProvider::class,

        Illuminate\Broadcasting\BroadcastServiceProvider::class,

        Illuminate\Cookie\CookieServiceProvider::class,

        Illuminate\Database\DatabaseServiceProvider::class,

        Illuminate\Encryption\EncryptionServiceProvider::class,

        Illuminate\Filesystem\FilesystemServiceProvider::class,

        Illuminate\Foundation\Providers\FoundationServiceProvider::class,

        Illuminate\Mail\MailServiceProvider::class,

        Illuminate\Notifications\NotificationServiceProvider::class,

        Illuminate\Pagination\PaginationServiceProvider::class,

        Illuminate\Queue\QueueServiceProvider::class,

        Illuminate\Redis\RedisServiceProvider::class,

        Illuminate\Session\SessionServiceProvider::class,

        Illuminate\Translation\TranslationServiceProvider::class,

        Illuminate\Validation\ValidationServiceProvider::class,

        Illuminate\View\ViewServiceProvider::class,

    ],

];