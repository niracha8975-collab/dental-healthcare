<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes for browser-based access.
| API routes are separated into routes/api.php.
|
*/


Route::get('/', function () {

    return response()->json([
        'application' => config('app.name'),
        'version' => env('APP_VERSION', '1.0.0'),
        'status' => 'running',
        'service' => 'Dental Healthcare Management System',
    ]);

});


/*
|--------------------------------------------------------------------------
| Admin Dashboard Placeholder
|--------------------------------------------------------------------------
|
| Authentication and permission middleware will be added
| when Admin Module is implemented.
|
*/

Route::prefix('admin')
    ->group(function (): void {

        Route::get('/', function () {

            return response()->json([
                'message' => 'Dental Healthcare Admin Dashboard',
                'status' => 'ready',
            ]);

        });

    });


/*
|--------------------------------------------------------------------------
| Healthcare Unit Information
|--------------------------------------------------------------------------
|
| Public information endpoint for health center identity.
|
*/

Route::get('/healthcare-unit', function () {

    return response()->json([

        'name' => env(
            'HEALTHCARE_UNIT_NAME',
            'โรงพยาบาลส่งเสริมสุขภาพตำบล'
        ),

        'province' => env(
            'HEALTHCARE_PROVINCE'
        ),

        'district' => env(
            'HEALTHCARE_DISTRICT'
        ),

        'subdistrict' => env(
            'HEALTHCARE_SUBDISTRICT'
        ),

    ]);

});