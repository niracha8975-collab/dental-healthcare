<?php


use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;

use App\Http\Controllers\PatientController;

use App\Http\Controllers\AppointmentController;

use App\Http\Controllers\AppointmentSlotController;

use App\Http\Controllers\DentalServiceController;

use App\Http\Controllers\DentalRecordController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\ReportController;

use App\Http\Controllers\AuditLogController;

use App\Http\Controllers\SettingController;



/*
|--------------------------------------------------------------------------
| API Version
|--------------------------------------------------------------------------
*/


Route::prefix('v1')->group(function(){





    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */


    Route::post(

        '/login',

        [AuthController::class,'login']

    );


    Route::post(

        '/register',

        [AuthController::class,'register']

    );





    /*
    |--------------------------------------------------------------------------
    | Public APIs
    |--------------------------------------------------------------------------
    */


    Route::get(

        '/settings/public',

        [SettingController::class,'public']

    );


    Route::get(

        '/services/active',

        [DentalServiceController::class,'active']

    );


    Route::get(

        '/appointment-slots',

        [AppointmentSlotController::class,'index']

    );





    /*
    |--------------------------------------------------------------------------
    | Authenticated Users
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:sanctum')

    ->group(function(){



        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */


        Route::post(

            '/logout',

            [AuthController::class,'logout']

        );


        Route::get(

            '/profile',

            [AuthController::class,'profile']

        );





        /*
        |--------------------------------------------------------------------------
        | Patient
        |--------------------------------------------------------------------------
        */


        Route::apiResource(

            'patients',

            PatientController::class

        );


        Route::get(

            '/patients/{patient}/dental-history',

            [

                PatientController::class,

                'dentalHistory'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Appointment
        |--------------------------------------------------------------------------
        */


        Route::apiResource(

            'appointments',

            AppointmentController::class

        )

        ->only([

            'index',

            'store',

            'show'

        ]);



        Route::put(

            '/appointments/{appointment}/confirm',

            [

                AppointmentController::class,

                'confirm'

            ]

        );



        Route::put(

            '/appointments/{appointment}/check-in',

            [

                AppointmentController::class,

                'checkIn'

            ]

        );



        Route::put(

            '/appointments/{appointment}/complete',

            [

                AppointmentController::class,

                'complete'

            ]

        );



        Route::put(

            '/appointments/{appointment}/cancel',

            [

                AppointmentController::class,

                'cancel'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Appointment Slot
        |--------------------------------------------------------------------------
        */


        Route::apiResource(

            'appointment-slots',

            AppointmentSlotController::class

        );



        Route::put(

            '/appointment-slots/{appointmentSlot}/toggle',

            [

                AppointmentSlotController::class,

                'toggleStatus'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Dental Services
        |--------------------------------------------------------------------------
        */


        Route::apiResource(

            'dental-services',

            DentalServiceController::class

        );



        Route::put(

            '/dental-services/{dentalService}/toggle',

            [

                DentalServiceController::class,

                'toggleStatus'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Dental Records
        |--------------------------------------------------------------------------
        */


        Route::apiResource(

            'dental-records',

            DentalRecordController::class

        );


        Route::post(

            '/dental-records/{dentalRecord}/treatment',

            [

                DentalRecordController::class,

                'addTreatment'

            ]

        );


        Route::post(

            '/dental-records/{dentalRecord}/odontogram',

            [

                DentalRecordController::class,

                'updateOdontogram'

            ]

        );


        Route::put(

            '/dental-records/{dentalRecord}/complete',

            [

                DentalRecordController::class,

                'complete'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */


        Route::get(

            '/notifications',

            [

                NotificationController::class,

                'index'

            ]

        );


        Route::get(

            '/notifications/unread-count',

            [

                NotificationController::class,

                'unreadCount'

            ]

        );


        Route::put(

            '/notifications/{notification}/read',

            [

                NotificationController::class,

                'markRead'

            ]

        );


        Route::put(

            '/notifications/read-all',

            [

                NotificationController::class,

                'markAllRead'

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Admin Management
        |--------------------------------------------------------------------------
        */


        Route::middleware('role:Admin')

        ->prefix('admin')

        ->group(function(){


            Route::get(

                '/users',

                [

                    AdminController::class,

                    'users'

                ]

            );


            Route::post(

                '/staff',

                [

                    AdminController::class,

                    'createStaff'

                ]

            );


            Route::put(

                '/users/{user}',

                [

                    AdminController::class,

                    'updateUser'

                ]

            );


            Route::put(

                '/users/{user}/role',

                [

                    AdminController::class,

                    'assignRole'

                ]

            );


            Route::put(

                '/users/{user}/status',

                [

                    AdminController::class,

                    'toggleStatus'

                ]

            );





            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */


            Route::get(

                '/reports/dashboard',

                [

                    ReportController::class,

                    'dashboard'

                ]

            );


            Route::get(

                '/reports/appointments',

                [

                    ReportController::class,

                    'appointments'

                ]

            );


            Route::get(

                '/reports/treatments',

                [

                    ReportController::class,

                    'treatments'

                ]

            );





            /*
            |--------------------------------------------------------------------------
            | Audit Logs
            |--------------------------------------------------------------------------
            */


            Route::get(

                '/audit-logs',

                [

                    AuditLogController::class,

                    'index'

                ]

            );


            Route::get(

                '/audit-logs/{auditLog}',

                [

                    AuditLogController::class,

                    'show'

                ]

            );





            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */


            Route::apiResource(

                'settings',

                SettingController::class

            );



            Route::post(

                '/settings/bulk-update',

                [

                    SettingController::class,

                    'bulkUpdate'

                ]

            );


        });



    });



});