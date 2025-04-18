<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// ====================== RUTE UMUM (TANPA LOGIN) ======================
Route::get('/', [LandingPageController::class, 'show'])->name('home');
Route::get('/products', [ProductController::class, 'allProducts'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('show');
Route::get('/store/{slug}', [ProductController::class, 'storeProducts'])->name('store');
Route::post('/wishlist/add', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove/{id}', [ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
Route::get('/profile/edit-buyer', function () {
    return view('profile.partials.update-buyer'); // Pastikan path ini sesuai dengan lokasi file Anda
})
    ->middleware('auth')
    ->name('profile.edit.buyer');

// ====================== RUTE BUYER (CUSTOMER) ======================
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('carts.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });

    // Checkout
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/token', [CheckoutController::class, 'getToken'])->name('checkout.getToken');
        Route::post('/update-payment', [CheckoutController::class, 'updatePayment'])->name('checkout.updatePayment');
    });

    // Orders (Buyer)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('{order}/payment-token', [OrderController::class, 'getPaymentToken'])->name('orders.payment-token');
    });

    // Account & Orders Management
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account.index');
        Route::get('/orders/all', [AccountController::class, 'allOrders'])->name('orders.all');
        Route::get('/orders/unpaid', [AccountController::class, 'unpaidOrders'])->name('orders.unpaid');
        Route::get('/orders/processing', [AccountController::class, 'processingOrders'])->name('orders.processing');
        Route::get('/orders/shipped', [AccountController::class, 'shippedOrders'])->name('orders.shipped');
        Route::get('/orders/completed', [AccountController::class, 'completedOrders'])->name('orders.completed');
        Route::get('/orders/cancelled', [OrderController::class, 'cancelled'])->name('orders.cancelled');
        Route::get('/reviews', [AccountController::class, 'reviews'])->name('reviews.index');
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::get('/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    });
});

// ====================== RUTE SELLER ======================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':seller'])
    ->prefix('seller')

    ->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('seller.dashboard');
        })->name('seller.dashboard');

        // Store Management
        Route::prefix('store')->group(function () {
            Route::get('/', [StoreController::class, 'index'])->name('seller.store.index');
            Route::get('/create', [StoreController::class, 'create'])->name('seller.store.create');
            Route::post('/', [StoreController::class, 'store'])->name('seller.store.store');
            Route::get('/edit/{store}', [StoreController::class, 'edit'])->name('seller.store.edit');
            Route::put('/{store}', [StoreController::class, 'update'])->name('seller.store.update');
            Route::delete('/{store}', [StoreController::class, 'destroy'])->name('seller.store.destroy');
        });

        // Product Management
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'sellerProducts'])->name('seller.products.index');
            Route::get('/create', [ProductController::class, 'create'])->name('seller.products.create');
            Route::post('/', [ProductController::class, 'store'])->name('seller.products.store');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
            Route::put('/{product}', [ProductController::class, 'update'])->name('seller.products.update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

            // Image Handling
            Route::post('/upload-temp-image', [ProductController::class, 'uploadTempImage'])->name('products.upload-temp-image');
            Route::post('/remove-temp-image', [ProductController::class, 'removeTempImage'])->name('products.remove-temp-image');
            Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        });

        // Transactions Management
        Route::prefix('transactions')
            ->name('seller.transactions.') // Nama base: seller.transactions.
            ->group(function () {
                // Orders
                Route::prefix('orders')
                    ->name('orders.') // seller.transactions.orders.
                    ->group(function () {
                        Route::get('/', [SellerOrderController::class, 'index'])->name('orders'); // seller.transactions.orders.index
                        Route::get('/{order}', [SellerOrderController::class, 'show'])->name('show'); // seller.transactions.orders.show
                        Route::put('/{order}/update-status', [SellerOrderController::class, 'updateStatus'])->name('update-status'); // seller.transactions.orders.update-status
                    });

                // Payments
                Route::prefix('payments')
                    ->name('payments.') // seller.transactions.payments.
                    ->group(function () {
                        Route::get('/', [PaymentController::class, 'index'])->name('payments'); // seller.transactions.payments.index
                        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show'); // seller.transactions.payments.show
                    });

                // Shipping
                Route::prefix('shipping')
                    ->name('shipping.') // seller.transactions.shipping.
                    ->group(function () {
                        Route::get('/', [ShipmentController::class, 'index'])->name('shipping'); // seller.transactions.shipping.index
                        Route::get('/{shipping}', [ShipmentController::class, 'show'])->name('show'); // seller.transactions.shipping.show
                        Route::put('/{shipping}', [ShipmentController::class, 'update'])->name('update'); // seller.transactions.shipping.update
                    });

                // Cart
                Route::prefix('cart')
                    ->name('cart.') // seller.transactions.cart.
                    ->group(function () {
                        Route::get('/', [CartController::class, 'index'])->name('cart'); // seller.transactions.cart.index
                        Route::post('/reminder/{user}', [CartController::class, 'sendReminder'])->name('reminder'); // seller.transactions.cart.reminder
                    });

                // Reports
                Route::prefix('reports')
                    ->name('reports.') // seller.transactions.reports.
                    ->group(function () {
                        Route::get('/', [ReportController::class, 'index'])->name('reports'); // seller.transactions.reports.index
                        Route::get('/export', [ReportController::class, 'export'])->name('export'); // seller.transactions.reports.export
                    });
            });

        // Reviews
        Route::prefix('reviews')->group(function () {
            Route::get('/', [ReviewController::class, 'sellerReviews'])->name('seller.reviews.index');
            Route::post('/{review}/reply', [ReviewController::class, 'storeReply'])->name('seller.reviews.reply');
        });
    });

// ====================== RUTE LAINNYA ======================
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('payment.callback');

// Authentication
require __DIR__ . '/auth.php';
