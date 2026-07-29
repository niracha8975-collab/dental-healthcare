<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
         |--------------------------------------------------------------------------
         | Global Middleware
         |--------------------------------------------------------------------------
         |
         | Register application-wide middleware here.
         | Custom healthcare security middleware will be added in future sprints.
         |
         */

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /*
         |--------------------------------------------------------------------------
         | Exception Handling
         |--------------------------------------------------------------------------
         |
         | Centralized exception handling configuration.
         | API exception formatting will be extended later.
         |
         */

    })
    ->create();