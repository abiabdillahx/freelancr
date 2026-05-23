<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
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
Route::get('services',        [ServiceController::class, 'index']);
Route::get('services/{id}',   [ServiceController::class, 'show']);

Route::middleware('auth:api')->group(function () {

    Route::post('services/upload-image', [ServiceController::class, 'uploadImage'])
        ->middleware('role:freelancer');

    Route::middleware('role:freelancer')->group(function () {
        Route::post('services',           [ServiceController::class, 'store']);
        Route::put('services/{id}',       [ServiceController::class, 'update']);
        Route::delete('services/{id}',    [ServiceController::class, 'destroy']);
    });

    Route::get('orders', [OrderController::class, 'index']);

    Route::post('orders', [OrderController::class, 'store'])
        ->middleware('role:client');

    Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:freelancer');

    Route::put('orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->middleware('role:client');
});