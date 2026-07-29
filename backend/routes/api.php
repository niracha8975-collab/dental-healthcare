<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Dental Healthcare API
|
*/


Route::prefix('v1')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Public API
        |--------------------------------------------------------------------------
        */


        Route::get(
            '/health',
            function () {

                return response()->json([

                    'status' => 'ok',

                    'service' =>
                        'Dental Healthcare API',

                    'version' =>
                        'v1',

                ]);

            }
        );



        /*
        |--------------------------------------------------------------------------
        | Authentication API
        |--------------------------------------------------------------------------
        */


        Route::prefix('auth')
            ->group(function () {


                Route::post(
                    '/login',
                    [
                        \App\Http\Controllers\Api\AuthController::class,
                        'login'
                    ]
                );


                Route::post(
                    '/logout',
                    [
                        \App\Http\Controllers\Api\AuthController::class,
                        'logout'
                    ]
                );


            });



        /*
        |--------------------------------------------------------------------------
        | Protected API
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:sanctum')
            ->group(function () {



                /*
                |--------------------------------------------------------------------------
                | User Profile
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/profile',
                    [
                        \App\Http\Controllers\Api\ProfileController::class,
                        'show'
                    ]
                );



                /*
                |--------------------------------------------------------------------------
                | Appointment Module
                |--------------------------------------------------------------------------
                */

                Route::prefix('appointments')
                    ->group(function () {


                        Route::get(
                            '/',
                            [
                                \App\Http\Controllers\Api\AppointmentController::class,
                                'index'
                            ]
                        );


                        Route::post(
                            '/',
                            [
                                \App\Http\Controllers\Api\AppointmentController::class,
                                'store'
                            ]
                        );


                        Route::get(
                            '/{id}',
                            [
                                \App\Http\Controllers\Api\AppointmentController::class,
                                'show'
                            ]
                        );


                        Route::put(
                            '/{id}',
                            [
                                \App\Http\Controllers\Api\AppointmentController::class,
                                'update'
                            ]
                        );


                        Route::delete(
                            '/{id}',
                            [
                                \App\Http\Controllers\Api\AppointmentController::class,
                                'destroy'
                            ]
                        );


                    });



                /*
                |--------------------------------------------------------------------------
                | Patient Module
                |--------------------------------------------------------------------------
                */

                Route::prefix('patients')
                    ->group(function () {


                        Route::get(
                            '/me',
                            [
                                \App\Http\Controllers\Api\PatientController::class,
                                'profile'
                            ]
                        );


                        Route::put(
                            '/me',
                            [
                                \App\Http\Controllers\Api\PatientController::class,
                                'update'
                            ]
                        );


                    });



                /*
                |--------------------------------------------------------------------------
                | Notification Module
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/notifications',
                    [
                        \App\Http\Controllers\Api\NotificationController::class,
                        'index'
                    ]
                );



                /*
                |--------------------------------------------------------------------------
                | MyPCU Sync
                |--------------------------------------------------------------------------
                */

                Route::post(
                    '/mypcu/sync',
                    [
                        \App\Http\Controllers\Api\MyPCUController::class,
                        'sync'
                    ]
                );


            });


    });