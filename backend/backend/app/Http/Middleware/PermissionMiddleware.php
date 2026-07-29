<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * ตัวอย่างการใช้งาน
     *
     * middleware('permission:view-patient')
     * middleware('permission:view-patient,edit-patient')
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$permissions
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
        | Super Admin
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
        | Permission Check
        |--------------------------------------------------------------------------
        */

        if (!method_exists($user, 'hasAnyPermission')) {

            return response()->json([
                'success' => false,
                'message' => 'Permission method not implemented.'
            ], 500);

        }

        if (!$user->hasAnyPermission($permissions)) {

            return response()->json([
                'success' => false,
                'message' => 'Permission denied.',
                'required_permissions' => $permissions
            ], 403);

        }

        return $next($request);
    }
}