<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DentalRecordController;
use App\Http\Controllers\DentalTreatmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\HealthCheckController;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->group(function () {

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/citizen-login', [AuthController::class, 'citizenLogin']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/refresh', [AuthController::class, 'refresh']);

            Route::get('/profile', [AuthController::class, 'profile']);
            Route::get('/permission', [AuthController::class, 'permission']);

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Health Check
    |--------------------------------------------------------------------------
    */

    Route::get('/health', [HealthCheckController::class, 'index']);
    Route::get('/health/ping', [HealthCheckController::class, 'ping']);

});

/*
|--------------------------------------------------------------------------
| Protected API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->middleware(['auth:sanctum'])
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Patient
    |--------------------------------------------------------------------------
    */

    Route::apiResource('patients', PatientController::class);

    Route::get(
        'patients/cid/{cid}',
        [PatientController::class, 'searchByCID']
    );

    Route::get(
        'patients/{patient}/appointments',
        [PatientController::class, 'appointments']
    );

    Route::get(
        'patients/{patient}/dental-records',
        [PatientController::class, 'dentalRecords']
    );

    Route::get(
        'patients/{patient}/queues',
        [PatientController::class, 'queues']
    );

    Route::get(
        'patients/{patient}/timeline',
        [PatientController::class, 'timeline']
    );

    /*
    |--------------------------------------------------------------------------
    | Appointment
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'appointments',
        AppointmentController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */

    Route::prefix('queues')->group(function () {

        Route::get('/', [QueueController::class, 'index']);

        Route::post('/', [QueueController::class, 'store']);

        Route::post(
            '/appointment/{appointment}',
            [QueueController::class, 'appointmentQueue']
        );

        Route::post(
            '/call-next',
            [QueueController::class, 'callNext']
        );

        Route::put(
            '/{queue}/recall',
            [QueueController::class, 'recall']
        );

        Route::put(
            '/{queue}/start',
            [QueueController::class, 'start']
        );

        Route::put(
            '/{queue}/complete',
            [QueueController::class, 'complete']
        );

        Route::put(
            '/{queue}/skip',
            [QueueController::class, 'skip']
        );

        Route::get(
            '/display',
            [QueueController::class, 'display']
        );

        Route::get(
            '/history',
            [QueueController::class, 'history']
        );

    });

    /*
    |--------------------------------------------------------------------------
    | Dental Record
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'dental-records',
        DentalRecordController::class
    );

    Route::apiResource(
        'dental-treatments',
        DentalTreatmentController::class
    );

    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'notifications',
        NotificationController::class
    );

    Route::post(
        'notifications/bulk',
        [NotificationController::class, 'bulk']
    );

    /*
    |--------------------------------------------------------------------------
    | Report
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')->group(function () {

        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/patients', [ReportController::class, 'patientStatistics']);
        Route::get('/appointments', [ReportController::class, 'appointmentReport']);
        Route::get('/queue', [ReportController::class, 'queueReport']);
        Route::get('/treatment', [ReportController::class, 'treatmentReport']);
        Route::get('/dmft', [ReportController::class, 'dmft']);
        Route::get('/staff', [ReportController::class, 'staffPerformance']);
        Route::get('/indicator', [ReportController::class, 'indicator']);
        Route::get('/export-excel', [ReportController::class, 'exportExcel']);
        Route::get('/export-pdf', [ReportController::class, 'exportPDF']);

    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::prefix('admin')->group(function () {

            Route::get(
                '/settings',
                [AdminSettingController::class, 'index']
            );

            Route::put(
                '/settings',
                [AdminSettingController::class, 'update']
            );

            Route::get(
                '/users',
                [UserManagementController::class, 'index']
            );

            Route::post(
                '/users',
                [UserManagementController::class, 'store']
            );

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Audit
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view-audit')->group(function () {

        Route::get(
            '/audit-logs',
            [AuditLogController::class, 'index']
        );

        Route::get(
            '/audit/dashboard',
            [AuditLogController::class, 'dashboard']
        );

    });

});