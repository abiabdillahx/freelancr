<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ImgbbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use OpenApi\Attributes as OA;

class ServiceController extends Controller
{
    #[OA\Get(
        path: "/api/services",
        summary: "Daftar jasa freelance",
        tags: ["Services"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "category", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "category_id", in: "query", schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "min_price", in: "query", schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "max_price", in: "query", schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Daftar jasa"),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $services = Service::with(['user:id,name,avatar', 'category:id,name,slug'])
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->where('status', 'active')
            ->filter($request->all())
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data'    => $services,
        ]);
    }

    #[OA\Get(
        path: "/api/services/{service}",
        summary: "Detail jasa",
        tags: ["Services"],
        parameters: [
            new OA\Parameter(name: "service", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Detail jasa"),
            new OA\Response(response: 404, description: "Jasa tidak ditemukan"),
        ]
    )]
    public function show(Service $service): JsonResponse
    {
        $service->load(['user:id,name,avatar,bio', 'category:id,name,slug'])
            ->loadAvg('reviews as average_rating', 'rating')
            ->loadCount('reviews');

        if ($service->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Jasa tidak tersedia.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $service,
        ]);
    }

    #[OA\Post(
        path: "/api/services",
        summary: "Buat jasa baru",
        tags: ["Services"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["category_id", "title", "description", "price"],
                    properties: [
                        new OA\Property(property: "category_id", type: "integer"),
                        new OA\Property(property: "title", type: "string"),
                        new OA\Property(property: "description", type: "string"),
                        new OA\Property(property: "price", type: "integer"),
                        new OA\Property(property: "image", type: "string", format: "binary"),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Jasa berhasil dibuat"),
            new OA\Response(response: 403, description: "Akses ditolak"),
        ]
    )]
    public function store(Request $request, ImgbbService $imgbb): JsonResponse
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|integer|min:1000',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            try {
                $imageUrl = $imgbb->upload(
                    $request->file('image'),
                    name: Str::slug($validated['title'])
                );
            } catch (RuntimeException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload gambar: ' . $e->getMessage(),
                ], 502);
            }
        }

        $service = Service::create([
            'user_id'     => Auth::id(),
            'category_id' => $validated['category_id'],
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'image_url'   => $imageUrl,
            'status'      => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jasa berhasil dibuat.',
            'data'    => $service->load(['category:id,name,slug']),
        ], 201);
    }

    #[OA\Put(
        path: "/api/services/{service}",
        summary: "Update jasa",
        tags: ["Services"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "service", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Jasa berhasil diperbarui"),
            new OA\Response(response: 403, description: "Bukan milik owner"),
        ]
    )]
    public function update(Request $request, Service $service, ImgbbService $imgbb): JsonResponse
    {
        if ($service->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit jasa ini.',
            ], 403);
        }

        $validated = $request->validate([
            'category_id'  => 'sometimes|exists:categories,id',
            'title'        => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'price'        => 'sometimes|integer|min:1000',
            'status'       => ['sometimes', Rule::in(['active', 'inactive'])],
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            try {
                $validated['image_url'] = $imgbb->upload(
                    $request->file('image'),
                    name: Str::slug($validated['title'] ?? $service->title)
                );
            } catch (RuntimeException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload gambar: ' . $e->getMessage(),
                ], 502);
            }
        }

        unset($validated['image']);
        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jasa berhasil diperbarui.',
            'data'    => $service->fresh()->load(['category:id,name,slug']),
        ]);
    }

    #[OA\Delete(
        path: "/api/services/{service}",
        summary: "Hapus jasa",
        tags: ["Services"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "service", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Jasa berhasil dihapus"),
            new OA\Response(response: 403, description: "Bukan milik owner"),
        ]
    )]
    public function destroy(Service $service): JsonResponse
    {
        if ($service->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus jasa ini.',
            ], 403);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jasa berhasil dihapus.',
        ]);
    }

    #[OA\Post(
        path: "/api/services/upload-image",
        summary: "Upload gambar saja",
        tags: ["Services"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "URL gambar"),
        ]
    )]
    public function uploadImage(Request $request, ImgbbService $imgbb): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $url = $imgbb->upload($request->file('image'));
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload gambar: ' . $e->getMessage(),
            ], 502);
        }

        return response()->json([
            'success'   => true,
            'image_url' => $url,
        ]);
    }
}
