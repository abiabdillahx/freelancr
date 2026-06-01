<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use OpenApi\Attributes as OA;
use App\Http\Controllers\CurrencyController;
use App\Services\CurrencyService;

class GatewayController extends Controller
{
    #[
        OA\Get(
            path: "/api/gateway/currency",
            summary: "Konversi IDR ke USD via Gateway",
            tags: ["Gateway"],
            security: [["bearerAuth" => []]],
            parameters: [
                new OA\Parameter(
                    name: "amount",
                    in: "query",
                    required: true,
                    schema: new OA\Schema(type: "number", example: 50000),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Konversi berhasil",
                ),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
                new OA\Response(
                    response: 429,
                    description: "Terlalu banyak request",
                ),
            ],
        ),
    ]
    public function currency(
        Request $request,
        \App\Services\CurrencyService $currency,
    ) {
        $this->logRequest($request);
        $this->rateLimit($request);

        return app(CurrencyController::class)->convert($request, $currency);
    }

    #[
        OA\Get(
            path: "/api/gateway/services",
            summary: "List semua jasa via Gateway",
            tags: ["Gateway"],
            security: [["bearerAuth" => []]],
            responses: [
                new OA\Response(response: 200, description: "List jasa"),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
                new OA\Response(
                    response: 429,
                    description: "Terlalu banyak request",
                ),
            ],
        ),
    ]
    public function services(Request $request)
    {
        $this->logRequest($request);
        $this->rateLimit($request);

        return app(ServiceController::class)->index($request);
    }

    #[
        OA\Get(
            path: "/api/gateway/orders",
            summary: "List order milik user via Gateway",
            tags: ["Gateway"],
            security: [["bearerAuth" => []]],
            responses: [
                new OA\Response(response: 200, description: "List order"),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
                new OA\Response(
                    response: 429,
                    description: "Terlalu banyak request",
                ),
            ],
        ),
    ]
    public function orders(Request $request)
    {
        $this->logRequest($request);
        $this->rateLimit($request);

        return app(OrderController::class)->index($request);
    }

    #[
        OA\Get(
            path: "/api/gateway/categories",
            summary: "List kategori via Gateway",
            tags: ["Gateway"],
            security: [["bearerAuth" => []]],
            responses: [
                new OA\Response(response: 200, description: "List kategori"),
                new OA\Response(
                    response: 401,
                    description: "Token tidak valid",
                ),
                new OA\Response(
                    response: 429,
                    description: "Terlalu banyak request",
                ),
            ],
        ),
    ]
    public function categories(Request $request)
    {
        $this->logRequest($request);
        $this->rateLimit($request);

        return app(CategoryController::class)->index($request);
    }

    private function rateLimit(Request $request)
    {
        $key = "gateway:" . ($request->ip() ?? "unknown");

        if (RateLimiter::tooManyAttempts($key, 30)) {
            $seconds = RateLimiter::availableIn($key);
            abort(
                response()->json(
                    [
                        "success" => false,
                        "message" => "Terlalu banyak request. Coba lagi dalam {$seconds} detik.",
                    ],
                    429,
                ),
            );
        }

        RateLimiter::hit($key, 60);
    }

    private function logRequest(Request $request)
    {
        Log::channel("stack")->info("API Gateway Request", [
            "method" => $request->method(),
            "path" => $request->path(),
            "ip" => $request->ip(),
            "user_id" => auth()->id(),
        ]);
    }
}
