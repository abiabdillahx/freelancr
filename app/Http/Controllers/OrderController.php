<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    #[OA\Get(
        path: "/api/orders",
        summary: "Daftar pesanan user",
        tags: ["Orders"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Daftar pesanan"),
        ]
    )]
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $orders = Order::with(['service', 'review'])
                ->where('client_id', $user->id)
                ->latest()
                ->get();
        } else {
            $orders = Order::with(['service', 'client', 'review'])
                ->whereHas('service', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    #[OA\Post(
        path: "/api/orders",
        summary: "Buat pesanan baru",
        tags: ["Orders"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["service_id", "note"],
                properties: [
                    new OA\Property(property: "service_id", type: "integer"),
                    new OA\Property(property: "note", type: "string"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Pesanan berhasil dibuat"),
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'note'       => 'required|string|max:1000',
        ]);

        $service = Service::findOrFail($request->service_id);

        if ($service->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak bisa order jasa milik sendiri.',
            ], 403);
        }

        $order = Order::create([
            'service_id' => $request->service_id,
            'client_id'  => Auth::id(),
            'status'     => 'pending',
            'note'       => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat.',
            'data'    => $order->load('service'),
        ], 201);
    }

    #[OA\Put(
        path: "/api/orders/{order}/status",
        summary: "Update status pesanan",
        tags: ["Orders"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "order", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Status berhasil diupdate"),
        ]
    )]
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        if ($order->service->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak punya akses ke order ini.',
            ], 403);
        }

        $allowed = [
            'pending'     => ['in_progress'],
            'in_progress' => ['completed'],
        ];

        if (!isset($allowed[$order->status]) || !in_array($request->status, $allowed[$order->status])) {
            return response()->json([
                'success' => false,
                'message' => "Status tidak bisa diubah dari '{$order->status}' ke '{$request->status}'.",
            ], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diupdate.',
            'data'    => $order,
        ]);
    }

    #[OA\Put(
        path: "/api/orders/{order}/cancel",
        summary: "Batalkan pesanan",
        tags: ["Orders"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "order", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Pesanan dibatalkan"),
        ]
    )]
    public function cancel(Order $order)
    {
        if ($order->client_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak punya akses ke order ini.',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order hanya bisa dibatalkan saat status masih pending.',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibatalkan.',
            'data'    => $order,
        ]);
    }
}
