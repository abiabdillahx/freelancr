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

class ServiceController extends Controller
{
    // -----------------------------------------------------------------------
    // GET /api/services
    // Publik – semua role boleh mengakses. Mendukung filter & pencarian.
    // -----------------------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = Service::with(['user:id,name,avatar', 'category:id,name,slug'])
            ->where('status', 'active');

        // Filter by kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by kategori slug
        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        // Pencarian by judul atau deskripsi
        if ($request->filled('search')) {
            $keyword = '%' . $request->search . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('description', 'like', $keyword);
            });
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        $services = $query->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'data'    => $services,
        ]);
    }

    // -----------------------------------------------------------------------
    // GET /api/services/{id}
    // Publik – detail satu jasa beserta rata-rata rating.
    // -----------------------------------------------------------------------
    public function show(int $id): JsonResponse
    {
        $service = Service::with([
            'user:id,name,avatar,bio',
            'category:id,name,slug',
        ])
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);

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

    // -----------------------------------------------------------------------
    // POST /api/services   [freelancer only]
    // Membuat jasa baru. Gambar opsional; jika dikirim, di-upload ke Imgbb.
    // -----------------------------------------------------------------------
    public function store(Request $request, ImgbbService $imgbb): JsonResponse
    {
        $validated = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|integer|min:1000',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // maks 5 MB
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

    // -----------------------------------------------------------------------
    // PUT /api/services/{id}   [freelancer, owner only]
    // -----------------------------------------------------------------------
    public function update(Request $request, int $id, ImgbbService $imgbb): JsonResponse
    {
        $service = Service::findOrFail($id);

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

        // Upload gambar baru jika ada
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

        // Hapus key 'image' agar tidak masuk ke fillable
        unset($validated['image']);

        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jasa berhasil diperbarui.',
            'data'    => $service->fresh()->load(['category:id,name,slug']),
        ]);
    }

    // -----------------------------------------------------------------------
    // DELETE /api/services/{id}   [freelancer, owner only]
    // -----------------------------------------------------------------------
    public function destroy(int $id): JsonResponse
    {
        $service = Service::findOrFail($id);

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

    // -----------------------------------------------------------------------
    // POST /api/services/upload-image   [freelancer only]
    // Endpoint terpisah: upload gambar ke Imgbb, mengembalikan URL-nya saja.
    // Berguna jika frontend ingin preview sebelum simpan form jasa.
    // -----------------------------------------------------------------------
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
