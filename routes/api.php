<?php

// ============================================================
// routes/api.php  –  bagian Bagas (Services & Categories)
// Tempel snippet ini ke dalam file routes/api.php yang sudah ada.
// ============================================================

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// ------------------------------------------------------------------
// Publik: siapa pun bisa melihat daftar kategori & jasa
// ------------------------------------------------------------------
Route::get('categories', [CategoryController::class, 'index']);

Route::get('services',        [ServiceController::class, 'index']);
Route::get('services/{id}',   [ServiceController::class, 'show']);

// ------------------------------------------------------------------
// Protected: harus login (JWT)
// ------------------------------------------------------------------
Route::middleware('auth:api')->group(function () {

    // Upload gambar terpisah (pratinjau sebelum buat jasa)
    // Hanya freelancer – dijaga oleh RoleMiddleware di dalam controller
    Route::post('services/upload-image', [ServiceController::class, 'uploadImage'])
        ->middleware('role:freelancer');

    // CRUD jasa – hanya freelancer
    Route::middleware('role:freelancer')->group(function () {
        Route::post('services',           [ServiceController::class, 'store']);
        Route::put('services/{id}',       [ServiceController::class, 'update']);
        Route::delete('services/{id}',    [ServiceController::class, 'destroy']);
    });
});
