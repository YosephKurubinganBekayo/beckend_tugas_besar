<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        $user = $request->user();

        if (!$user) {

            return response()->json([
                'detail' => 'Unauthorized'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        $userRole = class_basename($user);

        $userRole = strtolower($userRole);

        if ($userRole !== strtolower($role)) {

            return response()->json([
                'detail' => 'Forbidden'
            ], 403);
        }
        return $next($request);
    }
    
}
