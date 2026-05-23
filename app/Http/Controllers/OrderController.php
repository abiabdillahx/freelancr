<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // GET /api/orders — list order milik user
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $orders = Order::with('service')
                ->where('client_id', $user->id)
                ->latest()
                ->get();
        } else {
            // freelancer: order yang masuk ke jasa miliknya
            $orders = Order::with('service', 'client')
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

    // POST /api/orders — buat order baru [client only]
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'note'       => 'required|string|max:1000',
        ]);

        $service = Service::findOrFail($request->service_id);

        // client tidak boleh order jasa milik sendiri
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

    // PUT /api/orders/{id}/status — update status [freelancer only]
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        $order = Order::with('service')->findOrFail($id);

        // pastikan order ini milik jasa si freelancer
        if ($order->service->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu tidak punya akses ke order ini.',
            ], 403);
        }

        // validasi alur status: pending -> in_progress -> completed
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

    // PUT /api/orders/{id}/cancel — cancel order [client only, hanya pending]
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        // pastikan order ini milik client yang login
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