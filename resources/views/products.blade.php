@extends('layouts.app')

@section('title', 'Products - TechnoShop')
@section('description', 'Browse our wide selection of products at TechnoShop')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="relative rounded-xl overflow-hidden mb-8 bg-gradient-to-r from-blue-600 to-purple-600">
                <div class="absolute inset-0 bg-black opacity-30"></div>
                <div class="relative z-10 py-12 px-6 md:px-12">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Semua Produk</h1>
                    <p class="text-white text-opacity-90 max-w-xl">Temukan berbagai produk berkualitas dengan harga terbaik
                        untuk kebutuhan Anda</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <div class="lg:w-1/4">
                    <div class="sticky top-24">
                        <!-- Mobile Filter Toggle -->
                        <div class="lg:hidden mb-4">
                            <button id="filterToggle"
                                class="w-full flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 text-gray-800 dark:text-white">
                                <span class="font-medium flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Filter & Sortir
                                </span>
                                <svg id="filterArrow" xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 transform transition-transform duration-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Filter Content -->
                        <div id="filterContent" class="lg:block hidden lg:block">
                            <form id="filterForm" action="{{ route('products.index') }}" method="GET">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif

                                <!-- Sort Options -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                        </svg>
                                        Urutkan
                                    </h2>
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="radio" name="sort" value="latest"
                                                {{ request('sort', 'latest') == 'latest' ? 'checked' : '' }}
                                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Terbaru</span>
                                        </label>
                                        <label
                                            class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="radio" name="sort" value="cheapest"
                                                {{ request('sort') == 'cheapest' ? 'checked' : '' }}
                                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Harga Terendah</span>
                                        </label>
                                        <label
                                            class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="radio" name="sort" value="expensive"
                                                {{ request('sort') == 'expensive' ? 'checked' : '' }}
                                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Harga Tertinggi</span>
                                        </label>
                                        <label
                                            class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="radio" name="sort" value="sold"
                                                {{ request('sort') == 'sold' ? 'checked' : '' }}
                                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Terlaris</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Kategori
                                    </h2>
                                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                        <label
                                            class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="radio" name="category" value=""
                                                {{ !request('category') ? 'checked' : '' }}
                                                class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Semua Kategori</span>
                                        </label>
                                        @foreach ($categories as $category)
                                            <label
                                                class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 cursor-pointer">
                                                <input type="radio" name="category" value="{{ $category->id }}"
                                                    {{ request('category') == $category->id ? 'checked' : '' }}
                                                    class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span>{{ $category->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Filter -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Range Harga
                                    </h2>
                                    <div class="flex flex-col space-y-4">
                                        <div class="flex flex-col space-y-2">
                                            <label class="text-sm text-gray-600 dark:text-gray-400">Harga Minimal</label>
                                            <div class="relative w-full">
                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                                <input type="text" id="min_price_display" placeholder="Min" 
                                                    value="{{ request('min_price') ? number_format(request('min_price'), 0, ',', '.') : '' }}"
                                                    class="w-full rounded-md border-gray-300 pl-10 pr-10 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                                <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price') }}">
                                                <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" id="clear-min-price">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col space-y-2">
                                            <label class="text-sm text-gray-600 dark:text-gray-400">Harga Maksimal</label>
                                            <div class="relative w-full">
                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                                                <input type="text" id="max_price_display" placeholder="Max" 
                                                    value="{{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') : '' }}"
                                                    class="w-full rounded-md border-gray-300 pl-10 pr-10 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                                <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price') }}">
                                                <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" id="clear-max-price">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            <button type="submit" id="apply-price-filter"
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors duration-300 text-sm">
                                                Terapkan Filter
                                            </button>
                                            <button type="button" id="reset-price-filter"
                                                class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-4 rounded-md transition-colors duration-300 text-sm">
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="lg:w-3/4">
                    <!-- Results Count and View Toggle -->
                    <div class="flex flex-wrap justify-between items-center mb-6">
                        <p class="text-gray-600 dark:text-gray-400">
                            Menampilkan <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> -
                            <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> dari
                            <span class="font-medium">{{ $products->total() }}</span> produk
                        </p>

                        <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                            <button id="gridViewBtn" class="p-2 rounded-md bg-blue-600 text-white"
                                aria-label="Grid View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button id="listViewBtn"
                                class="p-2 rounded-md bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                aria-label="List View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($products->isEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            
                            @if (request('min_price') || request('max_price'))
                                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Tidak ada produk dalam rentang harga ini</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    @if (request('min_price') && request('max_price'))
                                        Tidak ditemukan produk dengan harga Rp {{ number_format(request('min_price'), 0, ',', '.') }} - Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                    @elseif (request('min_price'))
                                        Tidak ditemukan produk dengan harga minimal Rp {{ number_format(request('min_price'), 0, ',', '.') }}
                                    @elseif (request('max_price'))
                                        Tidak ditemukan produk dengan harga maksimal Rp {{ number_format(request('max_price'), 0, ',', '.') }}
                                    @endif
                                </p>
                            @else
                                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Tidak ada produk yang ditemukan</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Coba ubah filter atau kata kunci pencarian Anda</p>
                            @endif
                            
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="{{ route('products.index') }}"
                                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition-colors duration-300">
                                    Lihat Semua Produk
                                </a>
                                
                                @if (request('min_price') || request('max_price'))
                                    <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['min_price', 'max_price'])) }}"
                                        class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-6 rounded-md transition-colors duration-300">
                                        Hapus Filter Harga
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Grid View (default) -->
                        <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1 group">
                                    <a href="{{ route('show', $product->slug) }}" class="block">
                                        <div class="relative overflow-hidden h-48">
                                            <img src="{{ asset('storage/' . $product->photos->first()->photo) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                            <!-- Category Badge -->
                                            <div
                                                class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $product->category->name ?? 'Uncategorized' }}
                                            </div>

                                            <!-- Quick Actions -->
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                <div
                                                    class="flex space-x-2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                                    <form action="{{ route('cart.add') }}" method="POST"
                                                        class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit"
                                                            class="bg-white text-gray-800 p-2 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-300 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <button 
                                                    onclick="event.preventDefault(); document.getElementById('wishlist-form-{{ $product->id }}').submit();"
                                                    class="bg-white text-gray-800 p-2 rounded-full hover:bg-red-600 hover:text-white transition-colors duration-300"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </button>
                                                
                                                <form id="wishlist-form-{{ $product->id }}" action="{{ route('wishlist.add') }}" method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                </form>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="p-4">
                                        <a href="{{ route('show', $product->slug) }}" class="block">
                                            <h3
                                                class="text-lg font-semibold text-gray-800 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                                {{ $product->name }}
                                            </h3>
                                        </a>

                                        <!-- Rating -->
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= round($product->rating ?? 0))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-gray-300 dark:text-gray-600"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">
                                                ({{ number_format($product->rating ?? 0, 1) }})
                                            </span>
                                        </div>

                                        <p class="text-gray-500 dark:text-gray-400 text-sm line-clamp-2 mb-3">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>

                                        <div class="flex justify-between items-center mt-4">
                                            <div>
                                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </p>
                                                @if ($product->stock <= 5 && $product->stock > 0)
                                                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                                        Stok tersisa {{ $product->stock }}
                                                    </p>
                                                @elseif($product->stock == 0)
                                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                        Stok habis
                                                    </p>
                                                @endif
                                            </div>

                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors duration-300 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- List View (hidden by default) -->
                        <div id="listView" class="hidden space-y-6">
                            @foreach ($products as $product)
                                <div
                                    class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                                    <div class="flex flex-col md:flex-row">
                                        <div class="relative md:w-1/3 group">
                                            <img src="{{ asset('storage/' . $product->photos->first()->photo) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-56 md:h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            <!-- Quick Actions -->
                                            <div
                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                <div
                                                    class="flex space-x-2 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                                    <form action="{{ route('cart.add') }}" method="POST"
                                                        class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit"
                                                            class="bg-white text-gray-800 p-2 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-300 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                    </button>
                                                    <button 
                                                    onclick="event.preventDefault(); document.getElementById('wishlist-form-{{ $product->id }}').submit();"
                                                    class="bg-white text-gray-800 p-2 rounded-full hover:bg-red-600 hover:text-white transition-colors duration-300"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </button>
                                                
                                                <form id="wishlist-form-{{ $product->id }}" action="{{ route('wishlist.add') }}" method="POST" style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                </form>
                                                </div>
                                            </div>

                                            <!-- Category Badge -->
                                            <div
                                                class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $product->category->name ?? 'Uncategorized' }}
                                            </div>
                                        </div>

                                        <div class="p-6 md:w-2/3">
                                            <a href="{{ route('show', $product->slug) }}" class="block">
                                                <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">
                                                    {{ $product->name }}
                                                </h3>
                                            </a>

                                            <!-- Rating -->
                                            <div class="flex items-center mb-3">
                                                <div class="flex text-yellow-400">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= round($product->rating ?? 0))
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-gray-300 dark:text-gray-600"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">
                                                    ({{ number_format($product->rating ?? 0, 1) }})
                                                </span>
                                            </div>

                                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                {{ Str::limit($product->description, 150) }}
                                            </p>

                                            <div class="flex flex-wrap justify-between items-center mt-4">
                                                <div>
                                                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </p>
                                                    @if ($product->stock <= 5 && $product->stock > 0)
                                                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                                            Stok tersisa {{ $product->stock }}
                                                        </p>
                                                    @elseif($product->stock == 0)
                                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                            Stok habis
                                                        </p>
                                                    @endif
                                                </div>
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit"
                                                        class="mt-4 sm:mt-0 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition-colors duration-300 flex items-center space-x-2 {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        <span>Tambah ke Keranjang</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-4 right-4 z-50 transform translate-y-10 opacity-0 transition-all duration-500 hidden">
        <div class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toastMessage">Produk ditambahkan ke keranjang</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter toggle for mobile
            const filterToggle = document.getElementById('filterToggle');
            const filterContent = document.getElementById('filterContent');
            const filterArrow = document.getElementById('filterArrow');

            if (filterToggle && filterContent && filterArrow) {
                filterToggle.addEventListener('click', function() {
                    filterContent.classList.toggle('hidden');
                    filterArrow.classList.toggle('rotate-180');
                });
            }

            // View toggle (Grid/List)
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            if (gridViewBtn && listViewBtn && gridView && listView) {
                gridViewBtn.addEventListener('click', function() {
                    gridView.classList.remove('hidden');
                    listView.classList.add('hidden');
                    gridViewBtn.classList.remove('bg-gray-200', 'text-gray-700', 'dark:bg-gray-700',
                        'dark:text-gray-300');
                    gridViewBtn.classList.add('bg-blue-600', 'text-white');
                    listViewBtn.classList.remove('bg-blue-600', 'text-white');
                    listViewBtn.classList.add('bg-gray-200', 'text-gray-700', 'dark:bg-gray-700',
                        'dark:text-gray-300');
                });

                listViewBtn.addEventListener('click', function() {
                    gridView.classList.add('hidden');
                    listView.classList.remove('hidden');
                    listViewBtn.classList.remove('bg-gray-200', 'text-gray-700', 'dark:bg-gray-700',
                        'dark:text-gray-300');
                    listViewBtn.classList.add('bg-blue-600', 'text-white');
                    gridViewBtn.classList.remove('bg-blue-600', 'text-white');
                    gridViewBtn.classList.add('bg-gray-200', 'text-gray-700', 'dark:bg-gray-700',
                        'dark:text-gray-300');
                });
            }

            // Handle form submission with radio buttons
            const radioInputs = document.querySelectorAll('input[type="radio"]');
            radioInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.name === 'sort' || this.name === 'category') {
                        document.getElementById('filterForm').submit();
                    }
                });
            });

            // Price formatting for Indonesian currency
            const minPriceDisplay = document.getElementById('min_price_display');
            const maxPriceDisplay = document.getElementById('max_price_display');
            const minPriceInput = document.getElementById('min_price');
            const maxPriceInput = document.getElementById('max_price');
            const applyPriceFilterBtn = document.getElementById('apply-price-filter');
            const filterForm = document.getElementById('filterForm');

            // Format input as currency
            function formatCurrency(input) {
                input.addEventListener('input', function(e) {
                    // Remove non-numeric characters
                    let value = this.value.replace(/\D/g, '');
                    
                    // Format with thousand separators
                    if (value) {
                        this.value = new Intl.NumberFormat('id-ID').format(value);
                    }
                });
            }

            if (minPriceDisplay && maxPriceDisplay) {
                formatCurrency(minPriceDisplay);
                formatCurrency(maxPriceDisplay);
            }

            // Handle form submission
            if (applyPriceFilterBtn && filterForm) {
                applyPriceFilterBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Convert formatted values to raw numbers
                    if (minPriceDisplay && minPriceInput) {
                        const minValue = minPriceDisplay.value.replace(/\./g, '');
                        minPriceInput.value = minValue || '';
                    }
                    
                    if (maxPriceDisplay && maxPriceInput) {
                        const maxValue = maxPriceDisplay.value.replace(/\./g, '');
                        maxPriceInput.value = maxValue || '';
                    }
                    
                    // Submit the form
                    filterForm.submit();
                });
            }

            // Toast notification for add to cart
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            // Check for flash messages
            @if(session('success'))
                showToast("{{ session('success') }}");
            @endif
            
            function showToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('hidden', 'opacity-0', 'translate-y-10');
                toast.classList.add('opacity-100', 'translate-y-0');
                
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => {
                        toast.classList.add('hidden');
                    }, 500);
                }, 3000);
            }

            // Price Range Reset functionality
            const resetPriceFilterBtn = document.getElementById('reset-price-filter');
            const clearMinPriceBtn = document.getElementById('clear-min-price');
            const clearMaxPriceBtn = document.getElementById('clear-max-price');

            if (resetPriceFilterBtn) {
                resetPriceFilterBtn.addEventListener('click', function() {
                    if (minPriceDisplay) minPriceDisplay.value = '';
                    if (maxPriceDisplay) maxPriceDisplay.value = '';
                    if (minPriceInput) minPriceInput.value = '';
                    if (maxPriceInput) maxPriceInput.value = '';
                    
                    // If there are other filters active, submit the form without price filters
                    if (document.querySelector('input[name="category"]:checked:not([value=""])') || 
                        document.querySelector('input[name="sort"]:checked:not([value="latest"])') ||
                        document.querySelector('input[name="search"]')) {
                        filterForm.submit();
                    } else {
                        // If no other filters, redirect to the base URL
                        window.location.href = '{{ route("products.index") }}';
                    }
                });
            }

            if (clearMinPriceBtn) {
                clearMinPriceBtn.addEventListener('click', function() {
                    if (minPriceDisplay) minPriceDisplay.value = '';
                    if (minPriceInput) minPriceInput.value = '';
                });
            }

            if (clearMaxPriceBtn) {
                clearMaxPriceBtn.addEventListener('click', function() {
                    if (maxPriceDisplay) maxPriceDisplay.value = '';
                    if (maxPriceInput) maxPriceInput.value = '';
                });
            }
        });
    </script>
@endsection

