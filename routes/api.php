<?php

use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

require __DIR__.'/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    // Meal plan builder route
    Route::post('/meal-plans/{meal_plan}/builder', [MealPlanController::class, 'storeBuild']);

    // API resources
    Route::apiResource('meal-plans', MealPlanController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('meals', MealController::class);
    Route::apiResource('users', UserController::class);

    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/validate', [OrderController::class, 'validate']);

    // Settings
    Route::put('/settings/profile', [UserController::class, 'updateMyProfile']);
});
