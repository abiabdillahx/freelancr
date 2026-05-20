<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[
        OA\Post(
            path: "/api/auth/register",
            summary: "Register user baru",
            tags: ["Auth"],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    required: ["name", "email", "password", "role"],
                    properties: [
                        new OA\Property(
                            property: "name",
                            type: "string",
                            example: "Thoriq",
                        ),
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "thoriq@test.com",
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "password123",
                        ),
                        new OA\Property(
                            property: "role",
                            type: "string",
                            enum: ["freelancer", "client"],
                            example: "freelancer",
                        ),
                    ],
                ),
            ),
            responses: [
                new OA\Response(
                    response: 201,
                    description: "Registrasi berhasil",
                ),
                new OA\Response(response: 422, description: "Validasi gagal"),
            ],
        ),
    ]
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

    #[
        OA\Post(
            path: "/api/auth/login",
            summary: "Login user",
            tags: ["Auth"],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(
                    required: ["email", "password"],
                    properties: [
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "thoriq@test.com",
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "password123",
                        ),
                    ],
                ),
            ),
            responses: [
                new OA\Response(response: 200, description: "Login berhasil"),
                new OA\Response(
                    response: 401,
                    description: "Email atau password salah",
                ),
                new OA\Response(response: 422, description: "Validasi gagal"),
            ],
        ),
    ]
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

    #[
        OA\Get(
            path: "/api/auth/profile",
            summary: "Ambil profil user yang sedang login",
            tags: ["Auth"],
            security: [["bearerAuth" => []]],
            responses: [
                new OA\Response(response: 200, description: "Data profil"),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
            ],
        ),
    ]
    public function profile()
    {
        return response()->json([
            "success" => true,
            "message" => "Data profil",
            "data" => ["user" => JWTAuth::user()],
        ]);
    }

    #[
        OA\Post(
            path: "/api/auth/logout",
            summary: "Logout user",
            tags: ["Auth"],
            security: [["bearerAuth" => []]],
            responses: [
                new OA\Response(response: 200, description: "Logout berhasil"),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
            ],
        ),
    ]
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            "success" => true,
            "message" => "Logout berhasil",
        ]);
    }
}
