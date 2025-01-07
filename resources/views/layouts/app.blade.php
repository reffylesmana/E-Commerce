<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeknoShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>

<body>
<!-- Navbar -->
<nav class="bg-white shadow-md overflow-x-hidden fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto px-4 flex justify-between items-center py-3">
        <!-- Left Section: Logo & Menu (Visible only in medium screens and above) -->
        <div class="flex items-center hidden sm:flex"> <!-- Visible only in md and above -->
            <!-- Logo -->
            <a href="/" class="text-lg font-bold text-gray-800">TeknoShop</a>
            <!-- Menu -->
            <ul class="ml-6 flex space-x-4 sm:space-x-6 sm:text-lg sm:text-gray-600 sm:justify-center sm:mt-2 sm:ml-10 sm:flex-row">
                <li><a href="/" class="text-gray-600 hover:text-blue-500">Beranda</a></li>
                <li><a href="/products" class="text-gray-600 hover:text-blue-500">Produk</a></li>
            </ul>
        </div>

        <!-- Middle Section: Search Bar (Visible always) -->
        <div class="flex-grow mx-4 sm:w-full sm:flex sm:justify-center">
            <form action="/search" method="GET" class="flex w-full max-w-lg">
                <!-- Input Search -->
                <input 
                    type="text" 
                    class="flex-1 py-2 px-4 border border-gray-300 border-r-0 rounded-l-full focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Cari produk" 
                    name="search" 
                    value="{{ request()->search ? request()->search : '' }}"
                >
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="bg-white border border-gray-300 border-l-0 rounded-r-full px-4 flex items-center justify-center hover:bg-gray-100 focus:ring-2 focus:ring-blue-500"
                >
                    <i class="bi bi-search text-gray-500"></i>
                </button>
            </form>
        </div>

        <!-- Right Section: Cart & User Icon -->
        <div class="flex items-center space-x-6 sm:flex-none">
            <!-- Cart -->
            <div class="relative">
                <a href="/carts" class="text-gray-600 hover:text-blue-500 text-lg">
                    <i class="bi bi-cart"></i>
                </a>
                <!-- Badge -->
                <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">
                    {{-- {{ $carts->count() > 0 ? $carts->count() : '0' }} --}}
                </span>
            </div>
            <!-- User Profile (Hidden on sm) -->
            <div class="relative hidden sm:flex"> <!-- Visible only in md and above -->
                <button 
                    class="flex items-center focus:outline-none hover:text-blue-500"
                    id="userDropdown"
                >
                    <img 
                        src="https://static.vecteezy.com/system/resources/previews/014/194/216/non_2x/avatar-icon-human-a-person-s-badge-social-media-profile-symbol-the-symbol-of-a-person-vector.jpg" 
                        alt="User" 
                        class="rounded-full w-8 h-8"
                    >
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar (Visible only in small screens) -->
    <div class="sm:hidden fixed bottom-0 left-0 w-full bg-white text-gray-600 py-2 z-50 shadow-lg">
        <div class="flex justify-around">
            <a href="/" class="text-gray-600 hover:text-blue-500 flex flex-col items-center">
                <i class="bi bi-house-door-fill"></i>
                <span class="text-xs">Home</span>
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-500 flex flex-col items-center">
                <i class="bi bi-boxes"></i>
                <span class="text-xs">Produk</span>
            </a>
            <a href="#" class="text-gray-600 hover:text-blue-500 flex flex-col items-center">
                <i class="bi bi-person-circle"></i>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </div>    
</nav>
    @yield('content')
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const swiper = new Swiper('.swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    </script>

</body>

</html>
