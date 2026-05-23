<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     *
     * Mengembalikan daftar semua kategori jasa yang tersedia.
     * Endpoint ini publik (tidak perlu autentikasi).
     */
    public function index(): JsonResponse
    {
        $categories = Category::select('id', 'name', 'slug')
            ->withCount('services')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}
