<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Dental Healthcare Management System
| API Routes
|--------------------------------------------------------------------------
|
| API routes for Flutter Mobile Application,
| Flutter Web Admin and External Integrations.
|
*/


/*
|--------------------------------------------------------------------------
| API Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | System Health Check
    |--------------------------------------------------------------------------
    |
    | Used by:
    | - Docker Health Check
    | - Load Balancer
    | - Monitoring System
    |
    */

    Route::get('/health', function () {

        return response()->json([

            'status' => 'success',

            'service' => 'Dental Healthcare Management System API',

            'version' => 'v1',

            'timestamp' => now(),

        ]);

    });


    /*
    |--------------------------------------------------------------------------
    | Public Routes
    |--------------------------------------------------------------------------
    |
    | Routes accessible without authentication.
    |
    | Future:
    | - Login
    | - Register
    | - Public Information
    |
    */


    Route::prefix('public')->group(function () {


        Route::get('/system-info', function () {

            return response()->json([

                'name' =>
                    'Dental Healthcare Management System',

                'organization' =>
                    'Rai Lak Thong Subdistrict Health Promoting Hospital',

            ]);

        });


    });



    /*
    |--------------------------------------------------------------------------
    | Protected Routes
    |--------------------------------------------------------------------------
    |
    | Authentication will be added in Sprint 1.2
    |
    | Planned Middleware:
    |
    | auth:sanctum
    | jwt
    | audit
    |
    */


    Route::middleware([])->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Patient Module
        |--------------------------------------------------------------------------
        |
        | Future:
        | - Patient Profile
        | - Dental History
        | - Treatment Records
        |
        */


        Route::prefix('patients')->group(function () {

            // Patient routes will be implemented later

        });



        /*
        |--------------------------------------------------------------------------
        | Appointment Module
        |--------------------------------------------------------------------------
        |
        | Future:
        | - Appointment Booking
        | - Approval Workflow
        | - Queue Status
        |
        */


        Route::prefix('appointments')->group(function () {

            // Appointment routes will be implemented later

        });



        /*
        |--------------------------------------------------------------------------
        | Dental Treatment Module
        |--------------------------------------------------------------------------
        |
        | Future:
        | - Examination
        | - Scaling
        | - Filling
        | - Extraction
        |
        */


        Route::prefix('dental')->group(function () {

            // Dental routes will be implemented later

        });



    });



    /*
    |--------------------------------------------------------------------------
    | Integration Routes
    |--------------------------------------------------------------------------
    |
    | External system communication.
    |
    | Future:
    | - MyPCU
    | - HDC
    | - HOSxP PCU
    |
    */


    Route::prefix('integration')->group(function () {


        Route::prefix('mypcu')->group(function () {

            // MyPCU API routes will be implemented later

        });


    });


});