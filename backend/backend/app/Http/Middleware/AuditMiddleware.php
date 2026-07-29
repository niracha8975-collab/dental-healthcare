<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $start = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        try {

            AuditLog::create([

                'user_id' => optional($request->user())->id,

                'http_method' => $request->method(),

                'url' => $request->fullUrl(),

                'route' => optional($request->route())->getName(),

                'ip_address' => $request->ip(),

                'user_agent' => substr(
                    (string) $request->userAgent(),
                    0,
                    1000
                ),

                'status_code' => $response->getStatusCode(),

                'response_time_ms' => round(
                    (microtime(true) - $start) * 1000,
                    2
                ),

                'request_at' => now(),

            ]);

        } catch (\Throwable $e) {

            /*
             |--------------------------------------------------------------
             | ไม่ให้ Audit Log ที่ผิดพลาดกระทบกับ Request หลัก
             |--------------------------------------------------------------
             */

            report($e);

        }

        return $response;
    }
}