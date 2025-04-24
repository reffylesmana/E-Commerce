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
                <div class="text-center" data-aos="fade-up">
                    <h1 class="text-4xl sm:text-6xl font-extrabold mb-4 leading-tight animate-pulse">
                        {{ __('messages.heading') }}
                    </h1>
                    <p class="text-xl sm:text-2xl mb-8 max-w-2xl mx-auto">
                        {{ __('messages.subtext') }}
                    </p>
                    <a href="{{ route('products.index', ['lang' => app()->getLocale()]) }}" 
                       class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-blue-100 transition-colors duration-1000 transform hover:scale-105 animate-bounce">
                        {{ __('messages.button') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute top-full left-0 right-0 h-[100px] bg-gradient-to-r from-blue-600 to-purple-600"></div>
    </section>


    <!-- Banner Section -->
    <section class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-6 sm:px-10">
            <div class="relative rounded-xl overflow-hidden shadow-lg" data-aos="fade-up">
                <div id="banner-carousel" class="relative">
                    <div class="banner-container overflow-hidden">
                        <div class="banner-wrapper flex transition-transform duration-500 ease-in-out h-64">
                            @foreach ($banners as $banner)
                                <div class="banner-item min-w-full">
                                    <a href="{{ $banner->link_url ?? '#' }}">
                                        <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}"
                                            class="w-full h-full object-cover">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol navigasi -->
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
                        @foreach ($banners as $index => $banner)
                            <button
                                class="banner-indicator w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300 {{ $index === 0 ? 'banner-indicator-active' : '' }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Categories -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">{{ __('messages.categories_heading') }}
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
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
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">
                {{ __('messages.featured_heading') }}
            </h2>
    
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-8">
                @foreach ($products as $index => $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl h-full flex flex-col min-h-[360px] sm:min-h-[500px]"
                        data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <a href="{{ route('show', $product->slug) }}" class="block h-full flex flex-col relative">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photos->first()->photo) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300"></div>
                            </div>
                            <div class="p-2 sm:p-4 flex-grow flex flex-col relative z-10">
                                <h3 class="font-semibold text-xs sm:text-lg mb-1 sm:mb-2 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">
                                    {{ $product->name }}
                                </h3>
    
                                <div class="flex items-center mb-1 sm:mb-2">
                                    <div class="flex">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 sm:w-5 sm:h-5 {{ $star <= $product->averageRating() ? 'text-yellow-400' : 'text-gray-400' }}"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .587l3.668 7.571L24 9.748l-6 5.848 1.416 8.264L12 18.897l-7.416 4.963L6 15.596 0 9.748l8.332-1.59z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-1 text-gray-600 dark:text-gray-400 text-[10px] sm:text-xs">
                                        ({{ number_format($product->averageRating(), 1) }})
                                    </span>
                                </div>
    
                                <p class="text-gray-600 dark:text-gray-300 mb-1 sm:mb-2 text-xs sm:text-sm line-clamp-2 sm:line-clamp-3">
                                    {{ $product->short_description }}
                                </p>
    
                                @if ($product->store && $product->store->alamat)
                                    <p class="text-blue-600 dark:text-blue-400 text-xs sm:text-sm mb-2">
                                        {{ $product->store->alamat }}
                                    </p>
                                @endif
    
                                <div class="flex justify-between items-center mt-auto">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold text-sm sm:text-lg">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="button" onclick="addToCart(event, {{ $product->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white p-1 sm:p-2 rounded-full transition-colors duration-200 transform group-hover:scale-105 group-hover:shadow-md">
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
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ __('messages.login_notice') }}</p>
                    <a href="{{ route('login') }}"
                        class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition">
                        {{ __('messages.login_button') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-6 sm:px-10">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">{{ __('messages.testimonials_heading') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($reviews as $review)
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md" data-aos="fade-up"
                        data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="flex items-center mb-4">
                            <img src="{{ $review->user->image ? asset('storage/images/' . $review->user->image) : asset('images/default-user.png') }}"
                                alt="User" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h3 class="font-semibold dark:text-white">{{ $review->user->name }}</h3>
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 .587l3.668 7.571L24 9.748l-6 5.848 1.416 8.264L12 18.897l-7.416 4.963L6 15.596 0 9.748l8.332-1.59z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Trust Badges -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ([['🚚', __('messages.badge_shipping')], ['🔄', __('messages.badge_returns')], ['✅', __('messages.badge_authentic')], ['🔒', __('messages.badge_secure')]] as $index => $badge)
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
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-center dark:text-white" data-aos="fade-up">{{ __('messages.blog_heading') }}</h2>
                <a href="{{ route('blogs.index') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200" data-aos="fade-up">
                    {{ __('messages.view_all_articles') }}
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($blogs as $index => $blog)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md transform transition duration-300 hover:scale-105"
                        data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 dark:text-white line-clamp-2">
                                {{ $blog->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-2 text-sm">
                                {{ $blog->published_at->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $blog->excerpt }}
                            </p>
                            <a href="{{ route('blogs.show', $blog->slug) }}"
                                class="text-blue-600 dark:text-blue-400 font-semibold hover:underline flex items-center">
                                {{ __('messages.read_more') }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
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
                    <h3 class="text-xl font-bold mb-4">{{ __('messages.about_title') }}</h3>
                    <p class="text-gray-400">{{ __('messages.about_description') }}</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ __('messages.customer_service') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('messages.contact_us') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('messages.faq') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('messages.shipping') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">{{ __('messages.warranty_support') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ __('messages.connect_with_us') }}</h3>
                    <div class="flex space-x-4">
                        @foreach (['Facebook', 'Github', 'Instagram'] as $social)
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">{{ $social }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ __('messages.payment_methods') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Transfer Bank', 'QRIS', 'E-Wallet', 'Kartu Kredit'] as $method)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $method }}</div>
                        @endforeach
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-4">{{ __('messages.shipping_partners') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['JNE', 'SiCepat', 'J&T', 'Pos', 'AnterAja'] as $partner)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $partner }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} TechnoShop. {{ __('messages.copyright') }} | <a href="#"
                        class="hover:text-white">{{ __('messages.privacy_policy') }}</a> | <a href="#"
                        class="hover:text-white">{{ __('messages.terms_conditions') }}</a></p>
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
