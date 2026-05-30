<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FreelancerController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::get('categories', [CategoryController::class, 'index']);
Route::get('currency', [CurrencyController::class, 'convert']);
Route::get('services',        [ServiceController::class, 'index']);
Route::get('services/{service}',   [ServiceController::class, 'show']);
Route::get('services/{service}/reviews', [ReviewController::class, 'byService']);
Route::get('freelancers', [FreelancerController::class, 'index']);
Route::get('freelancers/{id}', [FreelancerController::class, 'show']);

Route::middleware('auth:api')->group(function () {

    Route::post('services/upload-image', [ServiceController::class, 'uploadImage'])
        ->middleware('role:freelancer');

    Route::middleware('role:freelancer')->group(function () {
        Route::post('services',           [ServiceController::class, 'store']);
        Route::put('services/{service}',       [ServiceController::class, 'update']);
        Route::delete('services/{service}',    [ServiceController::class, 'destroy']);
    });

    Route::get('orders', [OrderController::class, 'index']);

    Route::post('orders', [OrderController::class, 'store'])
        ->middleware('role:client');

    Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:freelancer');

    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->middleware('role:client');

    Route::post('reviews', [ReviewController::class, 'store'])
        ->middleware('role:client');
});
