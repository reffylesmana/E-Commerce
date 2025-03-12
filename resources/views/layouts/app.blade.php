<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4f46e5">
    <title>@yield('title', 'TeknoShop')</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <meta name="description" content="@yield('description', 'Discover the latest in technology with TechStore.')">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-16 md:pb-0">
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
            <nav class="hidden lg:flex items-center space-x-6">
                <input type="search" placeholder="Search products..." class="px-4 py-2 rounded-full bg-white bg-opacity-20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
                @foreach(['Laptops', 'Smartphones', 'Gaming'] as $item)
                    <a href="#" class="hover:text-blue-200 transition-colors duration-200 text-sm uppercase tracking-wider">{{ $item }}</a>
                @endforeach
            </nav>
            
            <!-- Mobile Search and Cart -->
            <div class="flex items-center space-x-4">
                <!-- Search icon for mobile -->
                <button class="lg:hidden focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1" onclick="toggleSearch()">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <!-- Desktop icons -->
                <a href="/account" class="hidden lg:block focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
                
                <!-- Cart icon (visible on all screens) -->
                <a href="/carts" class="relative focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </a>
                
                <!-- Dark mode toggle (desktop only) -->
                <button onclick="toggleDarkMode()" class="hidden lg:block focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1">
                    <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Search Bar (hidden by default) -->
        <div id="mobile-search" class="lg:hidden bg-blue-700 p-4 hidden">
            <input type="search" placeholder="Search products..." class="w-full px-4 py-2 rounded-full bg-white bg-opacity-20 text-white placeholder-gray-200 focus:outline-none focus:ring-2 focus:ring-white">
        </div>
        
        <!-- Mobile Products Menu (hidden by default) -->
        <div id="mobile-products-menu" class="lg:hidden bg-blue-700 p-4 hidden">
            @foreach(['Laptops', 'Smartphones', 'Accessories', 'Gaming'] as $item)
                <a href="#" class="block py-2 hover:text-blue-200 transition-colors duration-200 text-sm uppercase tracking-wider">{{ $item }}</a>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs mt-1">Beranda</span>
            </a>
            
            <!-- Produk (Products) -->
            <a href="#" class="flex flex-col items-center" onclick="toggleProductsMenu(); return false;">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <span class="text-xs mt-1">Produk</span>
            </a>
            
            <!-- Akun (Account) -->
            <a href="/account" class="flex flex-col items-center">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs mt-1">Akun</span>
            </a>
            
            <!-- Setting -->
            <a href="/settings" class="flex flex-col items-center">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs mt-1">Setting</span>
            </a>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
        });

        function toggleDarkMode() {
            const html = document.documentElement;
            if (html.classList.contains("dark")) {
                html.classList.remove("dark");
                localStorage.setItem("theme", "light");
            } else {
                html.classList.add("dark");
                localStorage.setItem("theme", "dark");
            }
        }

        // Pastikan tema tetap tersimpan setelah refresh
        document.addEventListener("DOMContentLoaded", () => {
            if (localStorage.getItem("theme") === "dark") {
                document.documentElement.classList.add("dark");
            }
        });
        
        // Mobile search toggle
        function toggleSearch() {
            document.getElementById('mobile-search').classList.toggle('hidden');
            // Hide products menu when search is toggled
            document.getElementById('mobile-products-menu').classList.add('hidden');
        }
        
        // Mobile products menu toggle
        function toggleProductsMenu() {
            document.getElementById('mobile-products-menu').classList.toggle('hidden');
            // Hide search when products menu is toggled
            document.getElementById('mobile-search').classList.add('hidden');
        }
    </script>
</body>
</html>