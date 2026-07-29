<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * ตัวอย่างการใช้งาน:
     * middleware('role:admin')
     * middleware('role:admin,dentist')
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        $user = $request->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);

        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin Bypass
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($user, 'hasRole') &&
            $user->hasRole('super-admin')
        ) {

            return $next($request);

        }

        /*
        |--------------------------------------------------------------------------
        | Check Role
        |--------------------------------------------------------------------------
        */

        if (!method_exists($user, 'hasAnyRole')) {

            return response()->json([
                'success' => false,
                'message' => 'Role method not implemented.'
            ], 500);

        }

        if (!$user->hasAnyRole($roles)) {

            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'required_roles' => $roles
            ], 403);

        }

        return $next($request);
    }
}