<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Dental Healthcare Web Application
|
*/


/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('welcome');

});



/*
|--------------------------------------------------------------------------
| Authentication Pages
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {


        Route::get(
            '/login',
            [
                \App\Http\Controllers\Auth\LoginController::class,
                'index'
            ]
        )->name('login');


        Route::post(
            '/login',
            [
                \App\Http\Controllers\Auth\LoginController::class,
                'login'
            ]
        );


    });



/*
|--------------------------------------------------------------------------
| Authenticated Web System
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {



        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [
                \App\Http\Controllers\DashboardController::class,
                'index'
            ]
        )->name('dashboard');



        /*
        |--------------------------------------------------------------------------
        | Appointment Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('appointments')
            ->group(function () {


                Route::get(
                    '/',
                    [
                        \App\Http\Controllers\Admin\AppointmentController::class,
                        'index'
                    ]
                );


                Route::get(
                    '/calendar',
                    [
                        \App\Http\Controllers\Admin\AppointmentController::class,
                        'calendar'
                    ]
                );


            });



        /*
        |--------------------------------------------------------------------------
        | Patient Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('patients')
            ->group(function () {


                Route::get(
                    '/',
                    [
                        \App\Http\Controllers\Admin\PatientController::class,
                        'index'
                    ]
                );


                Route::get(
                    '/{id}',
                    [
                        \App\Http\Controllers\Admin\PatientController::class,
                        'show'
                    ]
                );


            });



        /*
        |--------------------------------------------------------------------------
        | Dental Service Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('dental')
            ->group(function () {


                Route::get(
                    '/services',
                    [
                        \App\Http\Controllers\Admin\DentalServiceController::class,
                        'index'
                    ]
                );


            });



        /*
        |--------------------------------------------------------------------------
        | System Settings
        |--------------------------------------------------------------------------
        */

        Route::prefix('settings')
            ->middleware('role:admin')
            ->group(function () {


                Route::get(
                    '/',
                    [
                        \App\Http\Controllers\Admin\SettingController::class,
                        'index'
                    ]
                );


            });


    });