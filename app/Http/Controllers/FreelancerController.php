<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FreelancerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()
            ->select('id', 'name', 'avatar', 'bio')
            ->where('role', 'freelancer');

        if ($request->filled('search')) {
            $keyword = '%' . $request->search . '%';
            $query->where(function ($builder) use ($keyword) {
                $builder->where('name', 'like', $keyword)
                    ->orWhere('bio', 'like', $keyword);
            });
        }

        $freelancers = $query
            ->with(['services' => function ($builder) {
                $builder->select('id', 'user_id', 'price')
                    ->withAvg('reviews as average_rating', 'rating')
                    ->withCount('orders');
            }])
            ->latest()
            ->paginate(12);

        $freelancers->getCollection()->transform(function (User $freelancer) {
            $services = $freelancer->services;

            $averageRating = $services->pluck('average_rating')->filter()->avg();
            $minPrice = $services->min('price');
            $ordersCount = $services->sum('orders_count');

            return [
                'id' => $freelancer->id,
                'name' => $freelancer->name,
                'avatar' => $freelancer->avatar,
                'bio' => $freelancer->bio,
                'services_count' => $services->count(),
                'min_price' => $minPrice,
                'average_rating' => $averageRating ? round($averageRating, 1) : null,
                'orders_count' => $ordersCount,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $freelancers,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $freelancer = User::query()
            ->select('id', 'name', 'avatar', 'bio')
            ->where('role', 'freelancer')
            ->with(['services' => function ($builder) {
                $builder->select('id', 'user_id', 'category_id', 'title', 'description', 'price', 'image_url')
                    ->with(['category:id,name,slug'])
                    ->withAvg('reviews as average_rating', 'rating')
                    ->withCount('orders');
            }])
            ->findOrFail($id);

        $services = $freelancer->services;
        $averageRating = $services->pluck('average_rating')->filter()->avg();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $freelancer->id,
                'name' => $freelancer->name,
                'avatar' => $freelancer->avatar,
                'bio' => $freelancer->bio,
                'services_count' => $services->count(),
                'min_price' => $services->min('price'),
                'average_rating' => $averageRating ? round($averageRating, 1) : null,
                'orders_count' => $services->sum('orders_count'),
                'services' => $services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'title' => $service->title,
                        'description' => $service->description,
                        'price' => $service->price,
                        'image_url' => $service->image_url,
                        'category' => $service->category?->name,
                        'rating' => $service->average_rating ? round($service->average_rating, 1) : null,
                        'orders_count' => $service->orders_count,
                    ];
                })->values(),
            ],
        ]);
    }
}
