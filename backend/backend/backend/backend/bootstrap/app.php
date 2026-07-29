<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(
    basePath: dirname(__DIR__)
)


    /*
    |--------------------------------------------------------------------------
    | Routing Configuration
    |--------------------------------------------------------------------------
    |
    | Register application routing files.
    |
    */

    ->withRouting(

        web: __DIR__ . '/../routes/web.php',

        api: __DIR__ . '/../routes/api.php',

        commands: __DIR__ . '/../routes/console.php',

        health: '/up',

    )


    /*
    |--------------------------------------------------------------------------
    | Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | Global middleware configuration.
    |
    */

    ->withMiddleware(function (Middleware $middleware) {


        /*
        |--------------------------------------------------------------------------
        | Global Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->append([

            \Illuminate\Http\Middleware\HandleCors::class,

        ]);


        /*
        |--------------------------------------------------------------------------
        | API Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->api([

            \Illuminate\Routing\Middleware\SubstituteBindings::class,

        ]);

    })


    /*
    |--------------------------------------------------------------------------
    | Exception Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized exception handling configuration.
    |
    */

    ->withExceptions(function (Exceptions $exceptions) {


        /*
        |--------------------------------------------------------------------------
        | Future Healthcare Exception Mapping
        |--------------------------------------------------------------------------
        |
        | Reserved for:
        |
        | - API Exception Response
        | - Audit Logging
        | - Security Monitoring
        |
        */


    })


    ->create();