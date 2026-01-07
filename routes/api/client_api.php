<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('categories/express-shop', [CategoryController::class, 'expressShop']);

Route::controller(ProductController::class)->group(function () {
    Route::get('products/home', 'home');
    Route::get('products/express-shop', 'expressShop');
    Route::apiResource('products', ProductController::class)->only('show', 'index');
});


Route::controller(OrderController::class)->group(function () {
    Route::post('/orders', 'store');
    Route::get('/orders/{order}', 'show');
});

