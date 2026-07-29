<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API routes for Dental Healthcare Management System.
| Designed for Flutter Mobile App, Admin Dashboard,
| and external healthcare integrations.
|
*/


/*
|--------------------------------------------------------------------------
| API Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {

    return response()->json([

        'status' => 'ok',

        'service' => 'Dental Healthcare API',

        'timestamp' => now(),

    ]);

});



/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
|
| Routes accessible without authentication.
| Authentication will be added through Sanctum middleware.
|
*/

Route::prefix('v1')
    ->group(function (): void {


        /*
        |--------------------------------------------------------------------------
        | System Information
        |--------------------------------------------------------------------------
        */

        Route::get('/info', function () {

            return response()->json([

                'application' => config('app.name'),

                'version' => env(
                    'APP_VERSION',
                    '1.0.0'
                ),

                'api_version' => 'v1',

            ]);

        });


    });



/*
|--------------------------------------------------------------------------
| Authentication Protected API
|--------------------------------------------------------------------------
|
| Future routes:
|
| - Patient Profile
| - Appointment Booking
| - Dental Service
| - Notifications
| - Reports
|
| Middleware:
|
| auth:sanctum
|
*/


Route::middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function (): void {


        Route::get('/user', function () {

            return response()->json([

                'message' => 'Authenticated User',

            ]);

        });


    });