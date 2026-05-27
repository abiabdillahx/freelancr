<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * POST /api/reviews [client only]
     *
     * Client can review only their own completed order, once per order.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:1000',
        ]);

        $order = Order::with(['service', 'review'])->findOrFail($validated['order_id']);

        if ($order->client_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak punya akses ke order ini.',
            ], 403);
        }

        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Review hanya bisa dibuat untuk order yang sudah completed.',
            ], 422);
        }

        if ($order->review) {
            return response()->json([
                'success' => false,
                'message' => 'Order ini sudah memiliki review.',
            ], 422);
        }

        $review = Review::create([
            'order_id' => $order->id,
            'rating'   => $validated['rating'],
            'comment'  => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil dibuat.',
            'data'    => $review->load(['order.service:id,title,user_id']),
        ], 201);
    }

    /**
     * GET /api/services/{id}/reviews
     *
     * Public list of reviews for a service.
     */
    public function byService(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $reviews = Review::with(['order.client:id,name,avatar'])
            ->whereHas('order', fn ($query) => $query->where('service_id', $service->id))
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }
}
