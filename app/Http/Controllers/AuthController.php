<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|string|email|unique:users",
            "password" => "required|string|min:6",
            "role" => "required|in:freelancer,client",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validasi gagal",
                    "errors" => $validator->errors(),
                ],
                422,
            );
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => $request->role,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(
            [
                "success" => true,
                "message" => "Registrasi berhasil",
                "data" => ["user" => $user, "token" => $token],
            ],
            201,
        );
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Validasi gagal",
                    "errors" => $validator->errors(),
                ],
                422,
            );
        }

        $credentials = $request->only("email", "password");

        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Email atau password salah",
                ],
                401,
            );
        }

        return response()->json([
            "success" => true,
            "message" => "Login berhasil",
            "data" => ["user" => JWTAuth::user(), "token" => $token],
        ]);
    }

    public function profile()
    {
        return response()->json([
            "success" => true,
            "message" => "Data profil",
            "data" => ["user" => JWTAuth::user()],
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            "success" => true,
            "message" => "Logout berhasil",
        ]);
    }
}
