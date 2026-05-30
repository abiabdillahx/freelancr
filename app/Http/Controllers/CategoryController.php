<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: "/api/categories",
        summary: "Daftar kategori jasa",
        tags: ["Categories"],
        responses: [
            new OA\Response(response: 200, description: "Daftar kategori"),
        ]
    )]
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}
