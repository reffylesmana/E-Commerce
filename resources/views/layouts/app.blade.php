<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4f46e5">
    <title>@yield('title', 'TeknoShop')</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'Discover the latest in technology with TechStore.')">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-16 md:pb-0"
    data-user-logged-in="{{ Auth::check() ? 'true' : 'false' }}">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold tracking-tighter flex items-center">
                    <img src="{{ asset('img/logo.png') }}" alt="TechStore Logo" class="h-10 w-10 mr-2">
                    <span class="hidden sm:inline">TechnoShop</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            @php
                // Fetch all categories
                $categories = \App\Models\Category::all();

                // Randomly select 3 categories
                $randomCategories = $categories->random(min(3, $categories->count()));
            @endphp

            <nav class="hidden lg:flex items-center space-x-6">
                <input type="search" placeholder="{{ __('messages.search_products') }}"
                    class="px-4 py-2 rounded-full bg-white bg-opacity-20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
                @foreach ($randomCategories as $category)
                    <a href="{{ route('products.index') }}?sort=latest&category={{ $category->id }}"
                        class="hover:text-blue-200 transition-colors duration-200 text-sm uppercase tracking-wider">{{ $category->name }}</a>
                @endforeach
            </nav>

            <!-- Mobile Search and Cart -->
            <div class="flex items-center space-x-4">
                <!-- Search icon for mobile -->
                <button class="lg:hidden focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1"
                    onclick="toggleSearch()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Language Selector -->
                {{-- <div class="flex items-center space-x-2">
                    <a href="{{ route('set.language', 'id') }}" class="hover:underline">ID</a>
                    <span>|</span>
                    <a href="{{ route('set.language', 'en') }}" class="hover:underline">EN</a>
                </div> --}}

                <!-- Notifications icon (desktop only) -->
                @if (Auth::check())
                    <div class="relative">
                        <button id="notification-button"
                            class="focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1 relative">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @php
                                $unreadCount = Auth::user()->unreadNotifications->count();
                            @endphp
                            @if ($unreadCount > 0)
                                <span id="notification-badge"
                                    class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown -->
                        <div id="notification-dropdown"
                            class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden z-50">
                            <div
                                class="p-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                <h3 class="font-medium text-gray-700 dark:text-gray-300">Notifikasi</h3>
                                <a href="{{ route('notifications.index') }}"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                @if (Auth::user()->notifications->count() > 0)
                                    @foreach (Auth::user()->notifications->take(5) as $notification)
                                        <a href="{{ $notification->data['action_url'] ?? route('notifications.index') }}"
                                            class="block p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->read_at ? '' : 'bg-blue-50 dark:bg-blue-900/10' }}">
                                            <div class="flex">
                                                @if (isset($notification->data['type']) && $notification->data['type'] == 'order')
                                                    <div
                                                        class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-full p-2 mr-3">
                                                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                    </div>
                                                @elseif(isset($notification->data['type']) && $notification->data['type'] == 'shipping')
                                                    <div
                                                        class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-full p-2 mr-3">
                                                        <svg class="h-5 w-5 text-purple-600 dark:text-purple-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @elseif(isset($notification->data['type']) && $notification->data['type'] == 'promo')
                                                    <div
                                                        class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-full p-2 mr-3">
                                                        <svg class="h-5 w-5 text-green-600 dark:text-green-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div
                                                        class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-3">
                                                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif

                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $notification->data['message'] ?? '' }}</p>
                                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                        <p>Tidak ada notifikasi</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Desktop icons -->
                <a href="{{ route('account.index') }}"
                    class="hidden lg:block focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>

                <!-- Cart icon (visible on all screens) -->
                <a href="{{ route('carts.index') }}"
                    class="relative focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if (Auth::check())
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                        @endphp
                        <span id="cart-count"
                            class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-4 w-4 flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">
                            {{ $cartCount }}
                        </span>
                    @else
                        <span id="cart-count"
                            class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-4 w-4 flex items-center justify-center hidden">
                            0
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar (hidden by default) -->
        <div id="mobile-search" class="lg:hidden bg-blue-700 p-4 hidden">
            <input type="search" placeholder="Search products..."
                class="w-full px-4 py-2 rounded-full bg-white bg-opacity-20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
        </div>

        <!-- Mobile Products Menu (hidden by default) -->
        <div id="mobile-products-menu" class="lg:hidden bg-blue-700 p-4 hidden">
            @foreach (['Laptops', 'Smartphones', 'Accessories', 'Gaming'] as $item)
                <a href="#"
                    class="block py-2 hover:text-blue-200 transition-colors duration-200 text-sm uppercase tracking-wider">{{ $item }}</a>
            @endforeach
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gradient-to-r from-blue-600 to-purple-600 text-white z-50">
        <div class="flex justify-around items-center py-3">
            <!-- Beranda (Home) -->
            <a href="/" class="flex flex-col items-center">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span class="text-xs mt-1">Beranda</span>
            </a>

            <!-- Produk (Products) -->
            <a href="/products" class="flex flex-col items-center" onclick="toggleProductsMenu(); return false;">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <span class="text-xs mt-1">Produk</span>
            </a>
            <!-- Notifikasi (Notifications) -->
            @if (Auth::check())
                <div class="relative">
                    <button onclick="toggleMobileNotifications()" class="flex flex-col items-center relative">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span class="text-xs mt-1">Notifikasi</span>
                        @php
                            $unreadCount = Auth::user()->unreadNotifications->count();
                        @endphp
                        @if ($unreadCount > 0)
                            <span
                                class="notification-count-mobile absolute -top-1 right-0 bg-red-500 text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                </div>
            @endif

            <!-- Akun (Account) -->
            <a href="/account" class="flex flex-col items-center">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs mt-1">Akun</span>
            </a>

            <!-- Setting -->
            <a href="/settings" class="flex flex-col items-center">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs mt-1">Setting</span>
            </a>
        </div>
    </nav>

    <!-- Mobile Notifications Panel (hidden by default) -->
    <div id="mobileNotificationsPanel" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-xl max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Notifications</h3>
                <button onclick="toggleMobileNotifications()" class="text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="divide-y">
                @if (Auth::check() && Auth::user()->notifications->count() > 0)
                    @foreach (Auth::user()->notifications as $notification)
                        <div class="notification-item p-4 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                            <div class="flex">
                                @if ($notification->type == 'App\Notifications\OrderStatusChanged')
                                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-2 mr-3">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                @elseif ($notification->type == 'App\Notifications\ShippingUpdate')
                                    <div class="flex-shrink-0 bg-green-100 rounded-full p-2 mr-3">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8">
                                            </path>
                                        </svg>
                                    </div>
                                @elseif ($notification->type == 'App\Notifications\NewPromotion')
                                    <div class="flex-shrink-0 bg-yellow-100 rounded-full p-2 mr-3">
                                        <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                            </path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="flex-shrink-0 bg-gray-100 rounded-full p-2 mr-3">
                                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <a href="#"
                                        onclick="markAsRead('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}'); return false;">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="p-4 text-center">
                        <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800">View
                            All Notifications</a>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <p>No notifications yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->

    <!-- Midtrans JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        < script src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" >
    </script>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Handle payment buttons
            const payButtons = document.querySelectorAll('.pay-button');

            payButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Show loading state
                    this.disabled = true;
                    const originalText = this.innerHTML;
                    this.innerHTML =
                        '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memuat...';

                    // Get payment token
                    const route = this.getAttribute('data-route');
                    const successUrl = this.getAttribute('data-success-url');
                    const pendingUrl = this.getAttribute('data-pending-url');

                    fetch(route, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            this.disabled = false;
                            this.innerHTML = originalText;

                            if (data.error) {
                                alert('Error: ' + data.error);
                                return;
                            }

                            // Open Midtrans Snap
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    // Redirect to success page or reload current page
                                    window.location.href = successUrl;
                                },
                                onPending: function(result) {
                                    // Redirect to success page or reload current page
                                    window.location.href = pendingUrl;
                                },
                                onError: function(result) {
                                    alert('Pembayaran Gagal: ' + result
                                        .status_message);
                                },
                                onClose: function() {
                                    // Do nothing when user closes the payment window
                                    console.log(
                                        'Customer closed the payment window');
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Reset button state
                            this.disabled = false;
                            this.innerHTML = originalText;

                            alert(
                                'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi nanti.'
                            );
                        });
                });
            });
        });
        flash messages
        initSweetAlert();
        // Function to initialize SweetAlert based on session data
        function initSweetAlert() {
            @if (session('sweet_alert'))
                const alertData = @json(session('sweet_alert'));
                Swal.fire({
                    icon: alertData.icon || 'info',
                    title: alertData.title || '',
                    text: alertData.text || '',
                    toast: alertData.toast || false,
                    position: alertData.position || 'center',
                    showConfirmButton: alertData.showConfirmButton !== undefined ? alertData.showConfirmButton :
                        true,
                    confirmButtonText: alertData.confirmButtonText || 'OK',
                    confirmButtonColor: alertData.confirmButtonColor || '#3085d6',
                    timer: alertData.timer || null,
                    timerProgressBar: alertData.timerProgressBar || false,
                    showCancelButton: alertData.showCancelButton || false,
                    cancelButtonText: alertData.cancelButtonText || 'Cancel',
                    cancelButtonColor: alertData.cancelButtonColor || '#d33',
                    footer: alertData.footer || '',
                    customClass: alertData.customClass || {},
                    backdrop: alertData.backdrop !== undefined ? alertData.backdrop : true,
                    allowOutsideClick: alertData.allowOutsideClick !== undefined ? alertData.allowOutsideClick :
                        true
                }).then((result) => {
                    if (result.isConfirmed && alertData.confirmCallback) {
                        window.location.href = alertData.confirmCallback;
                    } else if (result.isDismissed && alertData.cancelCallback && result.dismiss === Swal
                        .DismissReason.cancel) {
                        window.location.href = alertData.cancelCallback;
                    }
                });
            @endif

            // Legacy support for old flash message format
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: "{{ session('warning') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{{ session('info') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif
        }

        // Function to show SweetAlert programmatically
        function showAlert(options) {
            Swal.fire(options);
        }

        // Function to show a confirmation dialog
        function confirmAction(event, options) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: options.title || 'Apakah Anda yakin?',
                text: options.text || "Tindakan ini tidak dapat dibatalkan!",
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#3085d6',
                cancelButtonColor: options.cancelButtonColor || '#d33',
                confirmButtonText: options.confirmButtonText || 'Ya, lanjutkan!',
                cancelButtonText: options.cancelButtonText || 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form) {
                        form.submit();
                    } else if (options.confirmCallback) {
                        if (typeof options.confirmCallback === 'function') {
                            options.confirmCallback();
                        } else if (typeof options.confirmCallback === 'string') {
                            window.location.href = options.confirmCallback;
                        }
                    }
                } else if (result.isDismissed && options.cancelCallback) {
                    if (typeof options.cancelCallback === 'function') {
                        options.cancelCallback();
                    } else if (typeof options.cancelCallback === 'string') {
                        window.location.href = options.cancelCallback;
                    }
                }
            });
        }
    </script>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        function toggleDarkMode() {
            const html = document.documentElement; // Mengambil elemen <html>
            html.classList.toggle('dark'); // Menambahkan atau menghapus kelas 'dark'
            const isDark = html.classList.contains('dark');
            localStorage.setItem('darkMode', isDark); // Menyimpan preferensi pengguna di localStorage
        }

        // Inisialisasi dark mode dari localStorage saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            document.documentElement.classList.toggle('dark', darkMode);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all search inputs (desktop and mobile)
            const searchInputs = document.querySelectorAll('input[type="search"]');

            // Function to handle search
            function handleSearch(event) {
                if (event.key === 'Enter' || event.type === 'search') {
                    event.preventDefault();

                    const searchQuery = event.target.value.trim();
                    if (!searchQuery) return; // Don't search if query is empty

                    // Check if user is logged in
                    const isLoggedIn = document.body.getAttribute('data-user-logged-in') === 'true';

                    if (!isLoggedIn) {
                        // Show SweetAlert if user is not logged in
                        Swal.fire({
                            title: 'Login Required',
                            text: 'Silakan login terlebih dahulu untuk melakukan pencarian produk',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/login';
                            }
                        });
                    } else {
                        // Redirect to products page with search query
                        window.location.href = `/products?search=${encodeURIComponent(searchQuery)}`;
                    }
                }
            }

            // Add event listeners to all search inputs
            searchInputs.forEach(input => {
                input.addEventListener('keypress', handleSearch);
                input.addEventListener('search', handleSearch);
            });
        });
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
        });

        // Pastikan tema tetap tersimpan setelah refresh
        document.addEventListener("DOMContentLoaded", () => {
            if (localStorage.getItem("theme") === "dark") {
                document.documentElement.classList.add("dark");
            }

            // Create notification for flash messages instead of SweetAlert
            @if (session('success') && Auth::check())
                createNotification('success', "{{ session('success') }}");
            @endif

            @if (session('error') && Auth::check())
                createNotification('error', "{{ session('error') }}");
            @endif

            @if (session('warning') && Auth::check())
                createNotification('warning', "{{ session('warning') }}");
            @endif

            @if (session('info') && Auth::check())
                createNotification('info', "{{ session('info') }}");
            @endif

            // For non-logged in users, still use SweetAlert
            @if (session('success') && !Auth::check())
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('error') && !Auth::check())
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('warning') && !Auth::check())
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: "{{ session('warning') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif

            @if (session('info') && !Auth::check())
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: "{{ session('info') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            @endif
        });

        // Function to create notification via AJAX
        function createNotification(type, message) {
            if (!message) return;

            fetch('/notifications/create-system', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: type,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Update notification count if needed
                    if (data.success && data.count) {
                        const countElements = document.querySelectorAll(
                            '.notification-count, .notification-count-mobile');
                        countElements.forEach(el => {
                            el.textContent = data.count;
                            el.classList.remove('hidden');
                        });
                    }
                })
                .catch(error => console.error('Error creating notification:', error));
        }

        // Mobile search toggle
        function toggleSearch() {
            document.getElementById('mobile-search').classList.toggle('hidden');
            // Hide products menu when search is toggled
            document.getElementById('mobile-products-menu').classList.add('hidden');
            // Hide mobile notifications panel
            document.getElementById('mobileNotificationsPanel').classList.add('hidden');
        }

        // Mobile products menu toggle
        function toggleProductsMenu() {
            document.getElementById('mobile-products-menu').classList.toggle('hidden');
            // Hide search when products menu is toggled
            document.getElementById('mobile-search').classList.add('hidden');
            // Hide mobile notifications panel
            document.getElementById('mobileNotificationsPanel').classList.add('hidden');
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');

            // Close dropdown when clicking outside
            if (!dropdown.classList.contains('hidden')) {
                document.addEventListener('click', closeNotificationDropdown);
            } else {
                document.removeEventListener('click', closeNotificationDropdown);
            }
        }

        function closeNotificationDropdown(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const button = document.querySelector('button[onclick="toggleNotificationDropdown()"]');

            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeNotificationDropdown);
            }
        }

        // Toggle mobile notifications panel
        function toggleMobileNotifications() {
            const panel = document.getElementById('mobileNotificationsPanel');
            panel.classList.toggle('hidden');

            // Prevent scrolling on body when panel is open
            if (!panel.classList.contains('hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Mark notification as read and redirect
        function markAsRead(id, url) {
            fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update notification count
                        const countElements = document.querySelectorAll(
                            '.notification-count, .notification-count-mobile');
                        countElements.forEach(el => {
                            const newCount = parseInt(el.textContent) - 1;
                            if (newCount <= 0) {
                                el.classList.add('hidden');
                            } else {
                                el.textContent = newCount;
                            }
                        });

                        // Redirect to URL
                        window.location.href = url;
                    }
                })
                .catch(error => console.error('Error marking notification as read:', error));
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Hide all notification count badges
                        const countElements = document.querySelectorAll(
                            '.notification-count, .notification-count-mobile');
                        countElements.forEach(el => {
                            el.classList.add('hidden');
                        });

                        // Mark all notification items as read (remove blue background)
                        const notificationItems = document.querySelectorAll('.notification-item');
                        notificationItems.forEach(item => {
                            item.classList.remove('bg-blue-50');
                        });
                    }
                })
                .catch(error => console.error('Error marking all notifications as read:', error));
        }

        // Function to confirm delete actions
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: message || "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Function to confirm actions
        function confirmAction(event, title, message, confirmText) {
            event.preventDefault();
            const form = event.target.closest('form');

            Swal.fire({
                title: title || 'Are you sure?',
                text: message || "Do you want to proceed?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText || 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Function to update cart count in the header
        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;

                if (count > 0) {
                    cartCountElement.classList.remove('hidden');
                } else {
                    cartCountElement.classList.add('hidden');
                }
            }
        }
    </script>

    <script>
        // Toggle notification dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notification-button');
            const notificationDropdown = document.getElementById('notification-dropdown');

            if (notificationButton && notificationDropdown) {
                notificationButton.addEventListener('click', function() {
                    notificationDropdown.classList.toggle('hidden');

                    // Mark notifications as read when dropdown is opened
                    if (!notificationDropdown.classList.contains('hidden')) {
                        fetch('/notifications/mark-all-read', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                            .then(response => {
                                // Hide notification badge
                                const badge = document.getElementById('notification-badge');
                                if (badge) {
                                    badge.classList.add('hidden');
                                }
                            })
                            .catch(error => console.error('Error marking notifications as read:', error));
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event
                            .target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
