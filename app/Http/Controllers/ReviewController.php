<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Get(
        path: "/api/services/{service}/reviews",
        summary: "Daftar review per jasa",
        tags: ["Reviews"],
        parameters: [
            new OA\Parameter(name: "service", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Daftar review"),
        ]
    )]
    public function byService(Service $service)
    {
        $reviews = Review::whereHas('order', function ($q) use ($service) {
            $q->where('service_id', $service->id);
        })
            ->with('order.client:id,name,avatar')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }

    #[OA\Post(
        path: "/api/reviews",
        summary: "Buat review baru",
        tags: ["Reviews"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["order_id", "rating", "comment"],
                properties: [
                    new OA\Property(property: "order_id", type: "integer"),
                    new OA\Property(property: "rating", type: "integer", minimum: 1, maximum: 5),
                    new OA\Property(property: "comment", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Review berhasil dibuat"),
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|unique:reviews,order_id',
            'rating'   => 'required|integer|min:1,max:5',
            'comment'  => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->client_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak punya akses untuk mereview order ini.',
            ], 403);
        }

        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Kamu hanya bisa mereview order yang sudah selesai.',
            ], 422);
        }

        $review = Review::create([
            'order_id' => $request->order_id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dikirim.',
            'data'    => $review,
        ], 201);
    }
}
