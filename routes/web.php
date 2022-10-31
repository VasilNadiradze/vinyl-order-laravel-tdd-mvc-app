<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchProductsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [SearchProductsController::class, 'index']);
Route::post('/add_to_cart', [CartController::class, 'store']);
Route::get('/cart', [CartController::class, 'index']);
Route::delete('/cart/{id}', [CartController::class, 'destroy']);
Route::patch('/cart/{id}', [CartController::class, 'update']);
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'create']);

            