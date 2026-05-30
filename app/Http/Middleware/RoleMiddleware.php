<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user || $user->role !== $role) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Akses ditolak. Role tidak sesuai atau Anda belum login.",
                ],
                403,
            );
        }

        return $next($request);
    }
}
