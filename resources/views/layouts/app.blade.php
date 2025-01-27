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
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-md overflow-x-hidden fixed top-0 left-0 w-full z-50">
        <div class="container mx-auto px-4 flex justify-between items-center py-3">
            <!-- Left Section: Logo & Menu (Visible only in medium screens and above) -->
            <div class="flex items-center hidden sm:flex">
                <!-- Logo -->
                <img src="{{ asset('img/logo.png') }}" alt="TeknoShop Logo" class="h-8 w-auto">
                <!-- Menu -->
                <ul class="ml-6 flex space-x-4 sm:space-x-6 sm:text-lg sm:text-gray-600 sm:justify-center sm:mt-2 sm:ml-10 sm:flex-row">
                    <li><a href="/" class="text-gray-600 hover:text-blue-500">Beranda</a></li>
                    <li><a href="/products" class="text-gray-600 hover:text-blue-500">Produk</a></li>
                </ul>
            </div>

            <!-- Middle Section: Search Bar (Visible always) -->
            <div class="flex-grow mx-4 sm:w-full sm:flex sm:justify-center">
                <form action="/search" method="GET" class="flex w-full max-w-lg">
                    <input type="text" class="flex-1 py-2 px-4 border border-gray-300 border-r-0 rounded-l-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari produk" name="search" value="{{ request()->search ? request()->search : '' }}">
                    <button type="submit" class="bg-white border border-gray-300 border-l-0 rounded-r-full px-4 flex items-center justify-center hover:bg-gray-100 focus:ring-2 focus:ring-blue-500">
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
                    <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5"></span>
                </div>

                <!-- Hamburger Menu (visible on sm) -->
                <div class="relative sm:hidden">
                    <button class="text-gray-600 hover:text-blue-500 focus:outline-none" id="hamburgerMenuButton">
                        <i class="bi bi-list text-lg"></i>
                    </button>
                </div>

                <!-- Authentication Links -->
                @guest
                    <div class="hidden sm:flex space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-500">{{ __('Masuk') }}</a>
                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-500">{{ __('Daftar') }}</a>
                    </div>
                @endguest

                @auth
                    <div class="relative hidden sm:flex">
                        <button class="flex items-center focus:outline-none hover:text-blue-500" id="userDropdown">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-sm font-bold" title="{{ Auth::user()->name }}">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Modal for Profile and Logout -->
    <div class="fixed top-4 right-4 z-50 hidden bg-white shadow-lg border border-gray-300 rounded-lg p-4" id="profileModal">
        <div class="text-gray-700">
            @guest
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-500 text-white rounded-md text-lg font-medium hover:bg-white hover:text-blue-500 border border-blue-500 transition-all">
                        {{ __('Masuk') }}
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-white text-blue-500 rounded-md text-lg font-medium hover:bg-blue-500 hover:text-white border border-blue-500 transition-all">
                        {{ __('Daftar') }}
                    </a>
                </div>
            @endguest

            @auth

                <a href="/edit-profile" class="text-blue-500 hover:text-blue-700">Edit Profile</a>
            @endauth

            <hr class="my-4 border-t border-gray-300">

            <div class="flex flex-col space-y-3">
                <a href="{{ route('logout') }}" class="text-lg text-gray-700 hover:text-red-500" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>

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

        const hamburgerMenuButton = document.getElementById('hamburgerMenuButton');
        const profileModal = document.getElementById('profileModal');

        // Show profile modal
        const userDropdownButton = document.getElementById('userDropdown');
        userDropdownButton.addEventListener('click', () => {
            profileModal.classList.remove('hidden');
        });

        // Close profile modal when clicked outside
        window.addEventListener('click', (event) => {
            if (!event.target.closest('#userDropdown') && !event.target.closest('#profileModal')) {
                profileModal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
