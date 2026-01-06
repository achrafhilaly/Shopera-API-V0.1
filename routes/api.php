<?php

use App\Http\Controllers\MealController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/auth.php';

Route::get('meal-plans/home', [MealPlanController::class, 'home']);
Route::apiResource('meal-plans', MealPlanController::class)->only('show', 'index');
Route::get('products/home', [ProductController::class, 'home']);
Route::apiResource('products', ProductController::class)->only('show', 'index');
Route::apiResource('meals', MealController::class)->only('index', 'show');
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/media/upload', MediaController::class);
    Route::get('/images/{path}', function (string $path) {
        $disk = Storage::disk('s3');

        Log::info('Proxy image request', ['path' => $path]);
        if (! $disk->exists($path)) {
            Log::warning('Image not found on s3', ['path' => $path]);
            abort(404);
        }

        return $disk->response($path);
    })->where('path', '.*');

    // Meal plan builder route
    Route::post('/meal-plans/{meal_plan}/builder', [MealPlanController::class, 'storeBuild']);

    // API resources
    Route::apiResource('meal-plans', MealPlanController::class)->except('show');
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class)->except('show');
    Route::apiResource('meals', MealController::class)->except('index', 'show');
    Route::apiResource('users', UserController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Settings
    Route::put('/settings/profile', [UserController::class, 'updateMyProfile']);
});
