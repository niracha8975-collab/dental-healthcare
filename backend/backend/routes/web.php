<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Dental Healthcare Management System
| Web Routes
|--------------------------------------------------------------------------
|
| Web routes for Laravel application.
|
| Main user interfaces:
| - Flutter Mobile Application
| - Flutter Web Admin
|
| This file only handles server-side web routes.
|
*/


/*
|--------------------------------------------------------------------------
| System Landing Page
|--------------------------------------------------------------------------
|
| Basic system information page.
|
*/

Route::get('/', function () {

    return response()->json([

        'system' =>
            'Dental Healthcare Management System',

        'organization' =>
            'Rai Lak Thong Subdistrict Health Promoting Hospital',

        'status' =>
            'running',

        'timestamp' =>
            now(),

    ]);

});



/*
|--------------------------------------------------------------------------
| Health Monitoring
|--------------------------------------------------------------------------
|
| Used for:
| - Server monitoring
| - Availability checking
|
*/

Route::get('/health', function () {

    return response()->json([

        'status' =>
            'healthy',

        'application' =>
            config('app.name'),

        'environment' =>
            app()->environment(),

        'timestamp' =>
            now(),

    ]);

});



/*
|--------------------------------------------------------------------------
| Future Web Administration
|--------------------------------------------------------------------------
|
| Flutter Web Admin is separated from Laravel View Layer.
|
| Future internal routes:
|
| /admin/system-status
| /admin/logs
| /admin/configuration
|
*/


Route::prefix('internal')->group(function () {


    Route::get('/status', function () {


        return response()->json([

            'service' =>
                'Internal Monitoring',

            'status' =>
                'available',

        ]);


    });


});



/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
|
| Prevent exposing Laravel errors for unknown pages.
|
*/

Route::fallback(function () {


    return response()->json([

        'message' =>
            'Resource not found',

    ], 404);


});