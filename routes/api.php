<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('jwt.auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Orders
Route::middleware('jwt.auth')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store'])->middleware('role:client');
    Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->middleware('role:freelancer');
    Route::put('/{id}/cancel', [OrderController::class, 'cancel'])->middleware('role:client');
});