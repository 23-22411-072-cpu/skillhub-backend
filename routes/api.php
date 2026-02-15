<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController; 
use App\Http\Controllers\LocationController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProviderProfileController; 
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\OrderController; 

Route::middleware([\App\Http\Middleware\Cors::class])->group(function () {

    // PUBLIC ROUTES
    Route::get('/services', [ServiceController::class, 'index']); 
    Route::get('/locations', [LocationController::class, 'index']); 
    Route::get('/providers', [ProviderProfileController::class, 'index']); 
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // PROTECTED ROUTES (Require Login)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) { return $request->user(); });

        Route::post('/logout', [AuthController::class, 'logout']);
        // --- Order Management ---
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/customer', [OrderController::class, 'getCustomerOrders']);
        Route::get('/provider/orders', [OrderController::class, 'getProviderOrders']);
        Route::put('/provider/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);
        Route::put('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']); 
        Route::post('/orders/{id}/rate', [OrderController::class, 'submitRating']); 

        // --- Provider Profile Management ---
        Route::put('/provider/profile', [ProviderProfileController::class, 'updateOrCreateProfile']); 
        Route::get('/provider/profile', [ProviderProfileController::class, 'getProfile']); 

        // 🔥 FIXED: Added this route for linking services (CompleteProfile page uses this)
        Route::post('/provider/services', [ProviderProfileController::class, 'linkServices']); 
    }); 
});