<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingPageController::class, 'show'])->name('landing.page');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::middleware(['auth'])->group(function () {
    Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
    Route::post('/carts/add/{product}', [CartController::class, 'store'])->name('carts.store');
    Route::patch('/carts/{item}', [CartController::class, 'update'])->name('carts.update');
    Route::delete('/carts/{item}', [CartController::class, 'remove'])->name('carts.remove');
});

// Account
    Route::get('/account', [AccountController::class, 'index'])->name('account');


// Profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Seller Routes
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':seller'])->prefix('seller')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('seller.dashboard');
    })->name('seller.dashboard');

    // Store
    Route::prefix('store')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('seller.store.index');
        Route::get('/create', [StoreController::class, 'create'])->name('seller.store.create');
        Route::post('/', [StoreController::class, 'store'])->name('seller.store.store');
        Route::put('/{id}', [StoreController::class, 'update'])->name('seller.store.update');
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'sellerProducts'])->name('seller.products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('seller.products.create');
        Route::post('/', [ProductController::class, 'store'])->name('seller.products.store');
    });
});

// Authentication
require __DIR__ . '/auth.php';