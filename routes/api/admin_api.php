<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log, Route, Storage};

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/media/upload', MediaController::class);
    Route::get('/images/{path}', function (string $path) {
        /** @var Storage $disk */
        $disk = Storage::disk('s3');

        Log::info('Proxy image request', ['path' => $path]);
        if (!$disk->exists($path)) {
            Log::warning('Image not found on s3', ['path' => $path]);
            abort(404);
        }

        return $disk->response($path);
    })->where('path', '.*');


    // Meal plan builder route
    Route::post('/meal-plans/{meal_plan}/builder', [MealPlanController::class, 'storeBuild']);

    // API resources
    Route::apiResource('meal-plans', MealPlanController::class)->except('show', 'index');
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class)->except('show', 'index');
    Route::apiResource('meals', MealController::class)->except('index', 'show');
    Route::apiResource('users', UserController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Settings
    Route::put('/settings/profile', [UserController::class, 'updateMyProfile']);
});
