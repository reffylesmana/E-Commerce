<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SellerBlogController;
use App\Http\Controllers\SellerCartController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// ====================== RUTE UMUM (TANPA LOGIN) ======================
Route::get('/', [LandingPageController::class, 'show'])->name('home');
Route::get('/products', [ProductController::class, 'allProducts'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('show');
Route::get('/store/{slug}', [ProductController::class, 'storeProducts'])->name('store');
Route::post('/wishlist/add', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/{id}', [ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
Route::get('/profile/edit-buyer', function () {
    return view('profile.partials.update-buyer');
})
    ->middleware('auth')
    ->name('profile.edit.buyer');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blogs.show');
route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');

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
        Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('cart.applyDiscount');
        Route::post('/remove-discount', [CartController::class, 'removeDiscount'])->name('cart.removeDiscount');
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
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::post('/reviews/{id}/remove-image', [ReviewController::class, 'removeImage'])->name('reviews.removeImage');
        Route::post('/account/orders/{order}/mark-delivered', [OrderController::class, 'markAsDelivered'])->name('orders.mark-as-delivered');
        Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
        Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
        Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
        Route::get('/address/{id}', [AddressController::class, 'show'])->name('address.show');
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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('seller.dashboard');

        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('seller.dashboard.chart-data');

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
            Route::get('/edit/{product:id}', [ProductController::class, 'edit'])->name('seller.products.edit');
            Route::put('/{product:id}', [ProductController::class, 'update'])->name('seller.products.update');
            Route::delete('/{product:id}', [ProductController::class, 'destroy'])->name('seller.products.destroy');

            // Image Handling
            Route::post('/upload-temp-image', [ProductController::class, 'uploadTempImage'])->name('products.upload-temp-image');
            Route::post('/remove-temp-image', [ProductController::class, 'removeTempImage'])->name('products.remove-temp-image');
            Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
        });

        // Transactions Management
        Route::prefix('transactions')
            ->name('seller.transactions.')
            ->group(function () {
                // Orders
                Route::prefix('orders')
                    ->name('orders.')
                    ->group(function () {
                        Route::get('/', [SellerOrderController::class, 'index'])->name('orders');
                        Route::get('/{order}', [SellerOrderController::class, 'show'])->name('show');
                    });

                // Payments
                Route::prefix('payments')
                    ->name('payments.')
                    ->group(function () {
                        Route::get('/', [PaymentController::class, 'index'])->name('payments');
                        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
                    });

                // Shipping
                Route::prefix('shipping')
                    ->name('shipping.')
                    ->group(function () {
                        Route::get('/', [ShippingController::class, 'index'])->name('shipping');
                        Route::get('/{shipping}', [ShippingController::class, 'show'])->name('show');
                        Route::put('/{shipping}', [ShippingController::class, 'update'])->name('update');
                        Route::post('/{shipping}/send', [ShippingController::class, 'send'])->name('send');
                    });

                // Cart
                Route::prefix('cart')
                    ->name('cart.')
                    ->group(function () {
                        Route::get('/', [SellerCartController::class, 'index'])->name('cart');
                        Route::post('/reminder/{user}', [SellerCartController::class, 'sendReminder'])->name('reminder');
                    });
            });

        // Reviews
        Route::prefix('reviews')->group(function () {
            Route::get('/', [ReviewController::class, 'sellerReviews'])->name('seller.reviews.index');
            Route::post('/{review}/reply', [ReviewController::class, 'storeReply'])->name('seller.reviews.reply');
        });

        // Discounts
        Route::prefix('discounts')
            ->name('seller.discounts.')
            ->group(function () {
                Route::get('/', [DiscountController::class, 'index'])->name('index');
                Route::get('/create', [DiscountController::class, 'create'])->name('create');
                Route::post('/', [DiscountController::class, 'store'])->name('store');
                Route::get('/{discount}/edit', [DiscountController::class, 'edit'])->name('edit');
                Route::put('/{discount}', [DiscountController::class, 'update'])->name('update');
                Route::delete('/{discount}', [DiscountController::class, 'destroy'])->name('destroy');
            });

        // Banners
        Route::prefix('banners')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('seller.banners.index');
            Route::get('/create', [BannerController::class, 'create'])->name('seller.banners.create');
            Route::get('/{banner}/edit', [BannerController::class, 'edit'])->name('seller.banners.edit');
            Route::post('/update-order', [BannerController::class, 'updateOrder'])->name('seller.banners.update-order');
            Route::post('/', [BannerController::class, 'store'])->name('seller.banners.store');
            Route::put('/{banner}', [BannerController::class, 'update'])->name('seller.banners.update');
            Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('seller.banners.destroy');
        });

        // Blogs Management
        Route::prefix('blogs')->group(function () {
            Route::get('/', [SellerBlogController::class, 'index'])->name('seller.blogs.index');
            Route::get('/create', [SellerBlogController::class, 'create'])->name('seller.blogs.create');
            Route::post('/', [SellerBlogController::class, 'store'])->name('seller.blogs.store');
            Route::get('/{blog}/edit', [SellerBlogController::class, 'edit'])->name('seller.blogs.edit');
            Route::get('/{blog}', [SellerBlogController::class, 'show'])->name('seller.blogs.show');
            Route::put('/{blog}', [SellerBlogController::class, 'update'])->name('seller.blogs.update');
            Route::delete('/{blog}', [SellerBlogController::class, 'destroy'])->name('seller.blogs.destroy');
        });

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('seller.reports');
            Route::get('/export', [ReportController::class, 'export'])->name('seller.reports.export');
        });
    });

// ====================== RUTE LAINNYA ======================
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('payment.callback');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

// Authentication
require __DIR__ . '/auth.php';
