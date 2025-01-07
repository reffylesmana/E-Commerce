<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [LandingPageController::class, 'show']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/carts', [CartController::class, 'index']);
Route::post('/carts/add/{product}', [CartController::class, 'store']);



