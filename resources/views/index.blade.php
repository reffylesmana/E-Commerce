@extends('layouts.app')

@section('title', 'TechnoShop - Home')
@section('description',
    'Discover the latest in technology with TechStore. Shop laptops, smartphones, accessories, and
    gaming gear.')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-6 sm:px-10 relative z-10"> 
            <div class="flex flex-col items-center justify-center h-full"> 
                <!-- Centered Main Hero Content -->
                <div class="text-center" data-aos="fade-up">
                    <h1 class="text-4xl sm:text-6xl font-extrabold mb-4 leading-tight animate-pulse">
                        Discover the Future of Tech
                    </h1>
                    <p class="text-xl sm:text-2xl mb-8 max-w-2xl mx-auto">
                        Incredible deals on the latest gadgets that will transform your digital life
                    </p>
                    <a href="javascript:void(0);" 
                    onclick="checkLoginAndRedirect()" 
                    class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-blue-100 transition-colors duration-1000 transform hover:scale-105 animate-bounce">
                    Shop Now
                 </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-gray-50 dark:from-gray-900 to-transparent">
        </div>
    </section>

    <!-- Banner Section -->
    <section class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-6 sm:px-10">
            <div class="relative rounded-xl overflow-hidden shadow-lg" data-aos="fade-up">
                <div id="banner-carousel" class="relative">
                    <div class="banner-container overflow-hidden">
                        <div class="banner-wrapper flex transition-transform duration-500 ease-in-out h-64">
                            <!-- Banner Items -->
                            <div class="banner-item min-w-full">
                                <img src="{{ asset('images/banner1.jpg') }}" alt="Promo Banner 1"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="banner-item min-w-full">
                                <img src="{{ asset('images/banner2.jpg') }}" alt="Promo Banner 2"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="banner-item min-w-full">
                                <img src="{{ asset('images/banner3.jpg') }}" alt="Promo Banner 3"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button id="banner-prev"
                        class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-3 shadow-md hover:bg-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="banner-next"
                        class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-3 shadow-md hover:bg-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                        <button
                            class="banner-indicator w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300 banner-indicator-active"></button>
                        <button
                            class="banner-indicator w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300"></button>
                        <button
                            class="banner-indicator w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Shop by Category
            </h2>

            <div class="relative">
                <!-- Left Navigation Arrow -->
                <button id="prevBtn"
                    class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Categories Container -->
                <div id="categoriesContainer" class="overflow-hidden">
                    <div id="categoriesWrapper" class="transition-all duration-500 ease-in-out">
                        @php
                            // Get all categories from database
                            $allCategories = App\Models\Category::whereNull('deleted_at')->get();
                            $totalCategories = $allCategories->count();
                        @endphp

                        <!-- Create the staggered grid layout -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                            @foreach ($allCategories as $index => $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="category-item group transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg {{ $index >= 8 ? 'hidden' : '' }}"
                                    data-index="{{ $index }}" data-aos="fade-up"
                                    data-aos-delay="{{ ($index % 8) * 50 }}">
                                    <div
                                        class="bg-white dark:bg-gray-700 rounded-2xl p-4 sm:p-6 flex flex-col items-center transition-all duration-300 hover:bg-blue-50 dark:hover:bg-blue-900 h-full">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 mb-3 sm:mb-4 rounded-xl overflow-hidden">
                                            @if ($category->photo)
                                                <img src="{{ asset('storage/' . $category->photo) }}"
                                                    alt="{{ $category->name }}"
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @else
                                                <div
                                                    class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-xl sm:text-2xl font-bold">
                                                    {{ substr($category->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>

                                        <h3
                                            class="text-sm sm:text-base font-semibold text-center group-hover:text-blue-600 dark:text-white transition-colors duration-300">
                                            {{ $category->name }}
                                        </h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Navigation Arrow -->
                <button id="nextBtn"
                    class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $totalCategories <= 8 ? 'hidden' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Pagination Indicators -->
            <div id="mobileIndicators" class="flex justify-center mt-4 gap-2 sm:hidden">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Featured
                Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-8">
                @foreach ($products as $index => $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl"
                        data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <a href="{{ route('show', $product->slug) }}" class="block h-full flex flex-col relative">
                            <div class="relative aspect-w-1 aspect-h-1 overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photos->first()->photo) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300">
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 flex-grow flex flex-col relative z-10">
                                <h3
                                    class="font-semibold text-sm sm:text-lg mb-1 sm:mb-2 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">
                                    {{ $product->name }}</h3>
                                <div class="flex items-center mb-1 sm:mb-2">
                                    <div class="flex">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 {{ $star <= round($product->rating) ? 'text-yellow-400' : 'text-gray-300' }} fill-current"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span
                                        class="ml-1 text-gray-600 dark:text-gray-400 text-xs">({{ number_format($product->rating, 1) }})</span>
                                </div>
                                <!-- Description - hidden on mobile -->
                                <p
                                    class="text-gray-600 dark:text-gray-300 mb-4 text-sm line-clamp-3 flex-grow hidden sm:block">
                                    {{ $product->description }}
                                </p>
                                <div class="flex justify-between items-center mt-auto">
                                    <span
                                        class="text-blue-600 dark:text-blue-400 font-bold text-sm sm:text-lg">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="button" onclick="addToCart(event, {{ $product->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 sm:p-2 rounded-full transition-colors duration-200 transform group-hover:scale-105 group-hover:shadow-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-50 transition duration-300"></div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if (!auth()->check())
            <div class="text-center mt-8" data-aos="fade-up">
                <p class="text-gray-600 dark:text-gray-300 mb-4">Login untuk melihat lebih banyak produk</p>
                <a href="{{ route('login') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">Login
                    Sekarang</a>
            </div>
        @endif
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">What Our
                Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md" data-aos="fade-up"
                        data-aos-delay="{{ ($i - 1) * 100 }}">
                        <div class="flex items-center mb-4">
                            <img src="#" alt="User" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h3 class="font-semibold dark:text-white">John Doe</h3>
                                <div class="flex">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">"Amazing products and excellent customer service. I'm a
                            happy customer!"</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ([['🚚', 'Free Shipping'], ['🔄', 'Easy Returns'], ['✅', '100% Authentic'], ['🔒', 'Secure Payment']] as $index => $badge)
                    <div class="flex flex-col items-center" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <span class="text-4xl mb-2">{{ $badge[0] }}</span>
                        <h3 class="font-semibold dark:text-white">{{ $badge[1] }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Blog Preview -->
    <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Tech Blog</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach (['Top 10 Laptops for 2025', 'The Future of Smartphones', 'Essential Tech Accessories'] as $index => $title)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md" data-aos="fade-up"
                        data-aos-delay="{{ $index * 100 }}">
                        <img src="#" alt="{{ $title }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 dark:text-white">{{ $title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            <a href="#" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Read
                                More</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">About TechStore</h3>
                    <p class="text-gray-400">Empowering your digital lifestyle with cutting-edge technology and
                        unparalleled customer experience.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Customer Service</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping & Returns</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Warranty & Support</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        @foreach (['Facebook', 'Twitter', 'Instagram', 'LinkedIn'] as $social)
                            <a href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200">{{ $social }}</a>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Subscribe to our newsletter</h4>
                        <form class="flex">
                            <input type="email" placeholder="Your email" class="px-4 py-2 rounded-l-lg w-full">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-colors duration-200">Subscribe</button>
                        </form>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Payment Methods</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Visa', 'Mastercard', 'PayPal', 'Apple Pay'] as $method)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $method }}</div>
                        @endforeach
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-4">Shipping Partners</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['FedEx', 'UPS', 'DHL'] as $partner)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $partner }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} TechStore. All rights reserved. | <a href="#"
                        class="hover:text-white">Privacy Policy</a> | <a href="#" class="hover:text-white">Terms of
                        Service</a></p>
            </div>
        </div>
    </footer>
@endsection

<script>
        function checkLoginAndRedirect() {
        // Cek apakah pengguna sudah login
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

        if (!isLoggedIn) {
            // Tampilkan SweetAlert jika belum login
            Swal.fire({
                title: 'Login Required',
                text: 'Silakan login untuk melanjutkan ke halaman belanja.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman login
                    window.location.href = '{{ route('login') }}';
                }
            });
        } else {
            // Jika sudah login, redirect ke halaman produk
            window.location.href = '/products';
        }
    }
    function addToCart(event, productId) {
        event.preventDefault(); // Mencegah form submit

        // Cek apakah pengguna sudah login
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

        if (!isLoggedIn) {
            // Tampilkan SweetAlert jika belum login
            Swal.fire({
                title: 'Login Required',
                text: 'Silakan login untuk menambahkan produk ke keranjang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman login
                    window.location.href = '{{ route('login') }}';
                }
            });
        } else {
            // Jika sudah login, submit form
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Banner Carousel
        const bannerWrapper = document.querySelector('.banner-wrapper');
        const bannerItems = document.querySelectorAll('.banner-item');
        const bannerPrev = document.getElementById('banner-prev');
        const bannerNext = document.getElementById('banner-next');
        const bannerIndicators = document.querySelectorAll('.banner-indicator');

        if (bannerWrapper && bannerItems.length > 0) {
            let currentBannerIndex = 0;
            const totalBanners = bannerItems.length;

            // Function to update banner position
            function updateBannerPosition() {
                bannerWrapper.style.transform = `translateX(-${currentBannerIndex * 100}%)`;

                // Update indicators
                bannerIndicators.forEach((indicator, index) => {
                    if (index === currentBannerIndex) {
                        indicator.classList.add('opacity-100', 'banner-indicator-active');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.remove('opacity-100', 'banner-indicator-active');
                        indicator.classList.add('opacity-50');
                    }
                });
            }

            // Previous button
            if (bannerPrev) {
                bannerPrev.addEventListener('click', function() {
                    currentBannerIndex = (currentBannerIndex - 1 + totalBanners) % totalBanners;
                    updateBannerPosition();
                });
            }

            // Next button
            if (bannerNext) {
                bannerNext.addEventListener('click', function() {
                    currentBannerIndex = (currentBannerIndex + 1) % totalBanners;
                    updateBannerPosition();
                });
            }

            // Indicator buttons
            bannerIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    currentBannerIndex = index;
                    updateBannerPosition();
                });
            });

            // Auto-rotate banners every 5 seconds
            setInterval(function() {
                currentBannerIndex = (currentBannerIndex + 1) % totalBanners;
                updateBannerPosition();
            }, 5000);
        }

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const categoryItems = document.querySelectorAll('.category-item');
        const mobileIndicators = document.getElementById('mobileIndicators');

        if (!prevBtn || !nextBtn || categoryItems.length === 0) return;

        const totalCategories = {{ $totalCategories }};
        const isMobile = window.innerWidth < 640; // sm breakpoint in Tailwind

        // Desktop Configuration
        const desktopItemsPerRow = 4; // 4 items per row on desktop
        const desktopRowsPerView = 2; // 2 rows per view
        const desktopItemsPerView = desktopItemsPerRow * desktopRowsPerView; // 8 items visible at once
        const desktopItemsToSlide = desktopItemsPerRow / 2; // Slide 2 items at a time

        // Mobile Configuration
        const mobileItemsPerRow = 2; // 2 items per row on mobile
        const mobileRowsPerView = 2; // 2 rows per view
        const mobileItemsPerView = mobileItemsPerRow * mobileRowsPerView; // 4 items visible at once
        const mobileItemsToSlide = mobileItemsPerRow; // Slide 2 items at a time

        // Set initial values based on screen size
        let itemsPerRow = isMobile ? mobileItemsPerRow : desktopItemsPerRow;
        let rowsPerView = isMobile ? mobileRowsPerView : desktopRowsPerView;
        let itemsPerView = isMobile ? mobileItemsPerView : desktopItemsPerView;
        let itemsToSlide = isMobile ? mobileItemsToSlide : desktopItemsToSlide;

        let startIndex = 0;

        // Create mobile pagination indicators
        if (mobileIndicators) {
            const totalMobilePages = Math.ceil(totalCategories / mobileItemsPerView);

            for (let i = 0; i < totalMobilePages; i++) {
                const dot = document.createElement('button');
                dot.classList.add('h-2', 'w-2', 'rounded-full', 'transition-colors', 'duration-300');
                dot.dataset.page = i;
                if (i === 0) {
                    dot.classList.add('bg-blue-600');
                } else {
                    dot.classList.add('bg-gray-300');
                }
                dot.addEventListener('click', () => {
                    // Calculate the appropriate startIndex for mobile
                    startIndex = i * mobileItemsPerView;
                    updateCategories();
                    updateMobileIndicators();
                });
                mobileIndicators.appendChild(dot);
            }
        }

        // Function to update mobile indicators
        function updateMobileIndicators() {
            if (!mobileIndicators) return;

            const currentMobilePage = Math.floor(startIndex / mobileItemsPerView);
            const dots = mobileIndicators.querySelectorAll('button');

            dots.forEach((dot, index) => {
                if (index === currentMobilePage) {
                    dot.classList.add('bg-blue-600');
                    dot.classList.remove('bg-gray-300');
                } else {
                    dot.classList.remove('bg-blue-600');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        function updateCategories() {
            categoryItems.forEach((item, index) => {
                if (index >= startIndex && index < startIndex + itemsPerView) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });

            // Update visibility of navigation buttons
            if (prevBtn) {
                prevBtn.classList.toggle('hidden', startIndex === 0);
            }
            if (nextBtn) {
                nextBtn.classList.toggle('hidden', startIndex + itemsPerView >= totalCategories);
            }
        }

        // Initial update
        updateCategories();

        // Event listeners for navigation buttons
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                startIndex = Math.max(0, startIndex - itemsToSlide);
                updateCategories();
                updateMobileIndicators();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                startIndex = Math.min(totalCategories - itemsPerView, startIndex + itemsToSlide);
                updateCategories();
                updateMobileIndicators();
            });
        }

        // Recalculate itemsPerRow and update categories on window resize
        function handleResize() {
            const isCurrentlyMobile = window.innerWidth < 640;
            if (isMobile !== isCurrentlyMobile) {
                isMobile = isCurrentlyMobile;
                itemsPerRow = isMobile ? mobileItemsPerRow : desktopItemsPerRow;
                rowsPerView = isMobile ? mobileRowsPerView : desktopRowsPerView;
                itemsPerView = isMobile ? mobileItemsPerView : desktopItemsPerView;
                itemsToSlide = isMobile ? mobileItemsToSlide : desktopItemsToSlide;

                // Reset startIndex to keep the first items visible
                startIndex = 0;
                updateCategories();
                updateMobileIndicators();
            }
        }

        window.addEventListener('resize', handleResize);
    });
</script>
