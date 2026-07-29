<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Bootstrap the Laravel application instance.
| This file is executed before HTTP and CLI requests.
|
*/

return Application::configure(
    basePath: dirname(__DIR__)
)


/*
|--------------------------------------------------------------------------
| Routing Configuration
|--------------------------------------------------------------------------
|
| Define application route files.
|
*/

->withRouting(
    web: __DIR__.'/../routes/web.php',

    api: __DIR__.'/../routes/api.php',

    commands: __DIR__.'/../routes/console.php',

    health: '/up',
)


/*
|--------------------------------------------------------------------------
| Middleware Configuration
|--------------------------------------------------------------------------
|
| Register global middleware and API middleware.
|
*/

->withMiddleware(function (Middleware $middleware) {


    /*
    |--------------------------------------------------------------------------
    | Global Middleware
    |--------------------------------------------------------------------------
    */

    $middleware->use([

        \Illuminate\Http\Middleware\HandleCors::class,

        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

    ]);


    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    */

    $middleware->api([

        \Illuminate\Routing\Middleware\SubstituteBindings::class,

    ]);


    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    */

    $middleware->alias([

        'auth' =>
            \Illuminate\Auth\Middleware\Authenticate::class,


        'verified' =>
            \Illuminate\Auth\Middleware\RequirePassword::class,

    ]);


})


/*
|--------------------------------------------------------------------------
| Exception Handling
|--------------------------------------------------------------------------
|
| Centralized exception configuration.
|
*/

->withExceptions(function (Exceptions $exceptions) {


    /*
    |--------------------------------------------------------------------------
    | Future Healthcare Exception Mapping
    |--------------------------------------------------------------------------
    |
    | Custom exceptions will be added here:
    |
    | - PatientNotFoundException
    | - AppointmentConflictException
    | - MyPCUConnectionException
    |
    */


})
->create();