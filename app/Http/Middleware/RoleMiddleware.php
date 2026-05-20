<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->role !== $role) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Akses ditolak. Role tidak sesuai.",
                ],
                403,
            );
        }

        return $next($request);
    }
}
