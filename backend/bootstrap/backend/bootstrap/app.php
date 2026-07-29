<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

->withRouting(

    web: __DIR__.'/../routes/web.php',

    api: __DIR__.'/../routes/api.php',

    commands: __DIR__.'/../routes/console.php',

    health: '/up'

)

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
    | Middleware Alias
    |--------------------------------------------------------------------------
    */

    $middleware->alias([

        'role' => \App\Http\Middleware\RoleMiddleware::class,

        'permission' => \App\Http\Middleware\PermissionMiddleware::class,

        'audit' => \App\Http\Middleware\AuditMiddleware::class,

    ]);

})

->withExceptions(function (Exceptions $exceptions) {

    /*
    |--------------------------------------------------------------------------
    | Custom Exception Rendering
    |--------------------------------------------------------------------------
    */

    $exceptions->render(function (
        Throwable $e,
        $request
    ) {

        if ($request->expectsJson()) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

                'exception' => class_basename($e),

            ], method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500);
        }

    });

})

->create();
Middleware Flow
Request

↓

CORS

↓

Authentication (Sanctum)

↓

Role Middleware

↓

Permission Middleware

↓

Audit Middleware

↓

Controller

↓

Response
Routing ที่รองรับ
routes/

├── api.php
├── web.php
└── console.php
Middleware Alias
role

permission

audit
สามารถเรียกใช้งานได้ เช่น
Route::middleware([
    'auth:sanctum',
    'role:admin'
])->group(function () {

    //
});
หรือ
Route::middleware([
    'permission:view-patient'
])->group(function () {

    //
});
Health Check

Laravel 12 รองรับ Health Endpoint
GET /up
ใช้สำหรับ

* Docker Health Check
* Kubernetes Liveness Probe
* Load Balancer Health Check
* Uptime Monitoring
Exception Response (API)

ตัวอย่าง
{
    "success": false,
    "message": "Unauthenticated.",
    "exception": "AuthenticationException"
}
