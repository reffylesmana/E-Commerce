@extends('layouts.app')

@section('title', $product->name)

@section('content')

<style>
    @keyframes ping {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .animate-ping {
        animation: ping 1.5s infinite;
    }
</style>

    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                        <i class="iconify mr-2" data-icon="lucide:home"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="iconify text-gray-400" data-icon="lucide:chevron-right"></i>
                        <a href="/products"
                            class="ml-1 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Products</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="iconify text-gray-400" data-icon="lucide:chevron-right"></i>
                        <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
            <div class="md:flex">
                <!-- Product Images Section -->
                <div class="md:w-1/2 p-4 md:p-6">
                    <div class="relative h-80 md:h-96 rounded-xl overflow-hidden mb-4 group" id="mainImageContainer">
                        @if (count($photos) > 0)
                            <img id="mainImage" src="{{ asset('storage/' . $photos[0]->photo) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-contain transition-all duration-500">
                        @else
                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="iconify text-gray-400 text-6xl" data-icon="lucide:image"></i>
                            </div>
                        @endif

                        <!-- Image Navigation Arrows -->
                        @if (count($photos) > 1)
                            <button id="prevImageBtn"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 opacity-0 group-hover:opacity-100">
                                <i class="iconify text-blue-600 dark:text-blue-400" data-icon="lucide:chevron-left"></i>
                            </button>
                            <button id="nextImageBtn"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white dark:bg-gray-700 rounded-full p-2 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 opacity-0 group-hover:opacity-100">
                                <i class="iconify text-blue-600 dark:text-blue-400" data-icon="lucide:chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if (count($photos) > 1)
                        <div class="flex space-x-2 overflow-x-auto pb-2 snap-x">
                            @foreach ($photos as $index => $photo)
                                <div class="w-20 h-20 flex-shrink-0 cursor-pointer border-2 hover:border-blue-500 rounded-lg overflow-hidden snap-start {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }}"
                                    data-index="{{ $index }}" data-src="{{ asset('storage/' . $photo->photo) }}"
                                    onclick="changeMainImage(this)">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>

                        <!-- Mobile Pagination Indicators -->
                        <div id="mobileIndicators" class="flex justify-center mt-4 gap-2 md:hidden">
                            @foreach ($photos as $index => $photo)
                                <button
                                    class="h-2 w-2 rounded-full transition-colors duration-300 {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600' }}"
                                    data-index="{{ $index }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Details Section -->
                <div class="md:w-1/2 p-4 md:p-6 md:border-l dark:border-gray-700">
                    <div class="flex flex-col md:h-full">
                        <div>
                            <div class="flex justify-between items-start">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $product->name }}</h1>
                                <button
                                    class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-300">
                                    <i class="iconify text-2xl" data-icon="lucide:heart-fill"></i>
                                </button>
                            </div>

                            <div class="flex items-center mt-2">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-400' }}"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 .587l3.668 7.571L24 9.748l-6 5.848 1.416 8.264L12 18.897l-7.416 4.963L6 15.596 0 9.748l8.332-1.59z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span
                                    class="ml-2 text-gray-600 dark:text-gray-400 text-sm">({{ number_format($averageRating, 1) }})
                                    · {{ $product->views }} views</span>
                            </div>

                            <div class="mt-2 flex items-center">
                                <span class="text-gray-600 dark:text-gray-400">Category:</span>
                                <a href="/products?category={{ $product->category_id }}"
                                    class="ml-2 text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $category->name }}
                                </a>
                            </div>
                            
                            <div class="mt-2 flex items-center">
                                <span class="text-gray-600 dark:text-gray-400">Store:</span>
                                <a href="{{ route('store', $store->slug) }}"
                                    class="ml-2 flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    @if ($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}"
                                            class="w-5 h-5 rounded-full mr-1">
                                    @endif
                                    {{ $store->name }}
                                    @if ($store->is_official)
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 rounded-full">Official</span>
                                    @endif
                                </a>
                            </div>
                            <div class="mt-2 flex items-center">
                                <span class="text-gray-600 dark:text-gray-400">Alamat:</span>
                                @if ($product->store && $product->store->alamat)
                                <p class="ml-2 text-blue-600 dark:text-blue-400">
                                    {{ $product->store->alamat }}
                                </p>
                            @endif
                            </div>


                        </div>
                        <div class="mt-6">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            <div class="mt-1 flex items-center">
                                <span
                                    class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                                    <i class="iconify mr-1" data-icon="lucide:check"></i>
                                    In Stock ({{ $product->stock }})
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Description</h2>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                        </div>

                        <div class="mt-8 flex space-x-4 md:mt-auto">
                            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>

                            {{-- Di dalam bagian tombol wishlist --}}
                            @auth
                            @php
                                // Cek apakah produk ada di wishlist
                                $isInWishlist = auth()
                                    ->user()
                                    ->wishlists()
                                    ->where('product_id', $product->id)
                                    ->exists();
                            @endphp
                        
                            <!-- Tombol Wishlist -->
                            <button
                            class="text-{{ $isInWishlist ? 'red-600' : 'gray-400' }} hover:text-red-500 dark:hover:text-red-400 transition-colors duration-300 relative"
                            onclick="event.preventDefault(); document.getElementById('wishlist-form-{{ $product->id }}').submit()"
                            title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Tambahkan ke Wishlist' }}">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        
                            @if ($isInWishlist)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-red-600 animate-ping" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </div>
                            @endif
                        </button>
                        
                            <!-- Form untuk menambah atau menghapus dari wishlist -->
                            <form id="wishlist-form-{{ $product->id }}"
                                action="{{ $isInWishlist ? route('wishlist.remove', $product->id) : route('wishlist.add') }}"
                                method="POST" class="hidden">
                                @csrf
                                @if ($isInWishlist)
                                    @method('DELETE')
                                @endif
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                            </form>
                        @else
                            <!-- Tombol untuk login jika belum login -->
                            <button
                                class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors duration-300"
                                onclick="window.location.href='{{ route('login') }}'"
                                title="Login untuk menambahkan ke Wishlist">
                                <i class="iconify text-2xl" data-icon="lucide:heart"></i>
                            </button>
                        @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Specifications -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up"
            data-aos-delay="100">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Specifications</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">General</h3>
                        <ul class="space-y-3">
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Brand</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $store->name }}</span>
                            </li>
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Model</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $product->name }}</span>
                            </li>
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Size</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $product->size }}</span>
                            </li>
                            <li class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Weight</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $product->weight }} kg</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Information</h3>
                        <ul class="space-y-3">
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Category</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $category->name }}</span>
                            </li>
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Stock</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $product->stock }} units</span>
                            </li>
                            <li class="flex border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Views</span>
                                <span class="text-gray-900 dark:text-white w-2/3">{{ $product->views }}</span>
                            </li>
                            <li class="flex">
                                <span class="text-gray-500 dark:text-gray-400 w-1/3">Added On</span>
                                <span
                                    class="text-gray-900 dark:text-white w-2/3">{{ $product->created_at->format('M d, Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up"
            data-aos-delay="200">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Customer Reviews</h2>

                @if (isset($reviews) && $reviews->count() > 0)
                    <ul class="space-y-6">
                        @foreach ($reviews as $review)
                            <li class="border-b border-gray-200 dark:border-gray-700 pb-6">
                                <div class="flex items-start">
                                    <img src="{{ $review->user->image ? asset('storage/images/' . $review->user->image) : asset('images/default-user.png') }}"
                                        alt="User" class="w-12 h-12 rounded-full mr-4">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</span>
                                            <div class="flex ml-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-400 ?>' }}"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 .587l3.668 7.571L24 9.748l-6 5.848 1.416 8.264L12 18.897l-7.416 4.963L6 15.596 0 9.748l8.332-1.59z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $review->comment }}</p>
                                        @if (!empty($review->images))
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach (json_decode($review->images) as $image)
                                                <div class="relative overflow-hidden rounded-lg shadow-md group">
                                                    <img src="{{ asset('storage/reviews/' . $image) }}"
                                                        alt="Review Image"
                                                        class="w-full h-32 md:h-40 lg:h-48 object-contain" />
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-300 text-center py-8">No reviews yet.</p>
                @endif
            </div>
        </div>
        <!-- Related Products -->
        @if (isset($relatedProducts) && count($relatedProducts) > 0)
            <div class="mt-12" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div
                            class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                            <a href="{{ route('show', $relatedProduct->slug) }}"
                                class="block h-full flex flex-col relative">
                                <div class="relative aspect-w-1 aspect-h-1 overflow-hidden">
                                    @if (count($relatedProduct->photos) > 0)
                                        <img src="{{ asset('storage/' . $relatedProduct->photos[0]->photo) }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                    @else
                                        <div
                                            class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <i class="iconify text-gray-400 text-6xl" data-icon="lucide:image"></i>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300">
                                    </div>
                                </div>
                                <div class="p-4 flex-grow flex flex-col relative z-10">
                                    <h3
                                        class="font-semibold text-lg mb-2 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">
                                        {{ $relatedProduct->name }}</h3>
                                    <div class="flex items-center mb-2">
                                        <div class="flex">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 {{ $i <= $relatedProduct->averageRating ? 'text-yellow-400' : 'text-gray-400' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 .587l3.668 7.571L24 9.748l-6 5.848 1.416 8.264L12 18.897l-7.416 4.963L6 15.596 0 9.748l8.332-1.59z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-600 dark:text-gray-400 text-xs">
                                            ({{ number_format($relatedProduct->averageRating, 1) }})
                                        </span>
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm line-clamp-2 flex-grow">
                                        {{ $relatedProduct->description }}</p>
                                    <div class="flex justify-between items-center mt-auto">
                                        <span
                                            class="text-blue-600 dark:text-blue-400 font-bold">Rp{{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 text-sm transform group-hover:scale-105 group-hover:shadow-md">
                                                <i class="iconify" data-icon="lucide:shopping-cart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('[data-src]');
            const mobileIndicators = document.querySelectorAll('#mobileIndicators button');
            const prevImageBtn = document.getElementById('prevImageBtn');
            const nextImageBtn = document.getElementById('nextImageBtn');

            let currentImageIndex = 0;
            const maxImageIndex = thumbnails.length - 1;

            function changeMainImage(element) {
                const newSrc = element.dataset.src;
                const newIndex = parseInt(element.dataset.index);
                mainImage.classList.add('opacity-0');

                setTimeout(() => {
                    mainImage.src = newSrc;
                    mainImage.classList.remove('opacity-0');
                    currentImageIndex = newIndex;

                    thumbnails.forEach(thumb => {
                        thumb.classList.toggle('border-blue-500', parseInt(thumb.dataset.index) ===
                            currentImageIndex);
                        thumb.classList.toggle('border-transparent', parseInt(thumb.dataset
                            .index) !== currentImageIndex);
                    });

                    mobileIndicators.forEach(indicator => {
                        indicator.classList.toggle('bg-blue-600', parseInt(indicator.dataset
                            .index) === currentImageIndex);
                        indicator.classList.toggle('bg-gray-300', parseInt(indicator.dataset
                            .index) !== currentImageIndex);
                    });
                }, 300);
            }

            prevImageBtn.addEventListener('click', function() {
                if (currentImageIndex > 0) {
                    currentImageIndex--;
                    changeMainImage(thumbnails[currentImageIndex]);
                }
            });

            nextImageBtn.addEventListener('click', function() {
                if (currentImageIndex < maxImageIndex) {
                    currentImageIndex++;
                    changeMainImage(thumbnails[currentImageIndex]);
                }
            });

            // Display SweetAlert notifications for flash messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'bottom-end',
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
                    position: 'bottom-end',
                    showConfirmButton: false
                });
            @endif
        });

        // Make the changeMainImage function global so it can be called from HTML
        window.changeMainImage = function(element) {
            const newSrc = element.dataset.src;
            const newIndex = parseInt(element.dataset.index);
            const mainImage = document.getElementById('mainImage');
            mainImage.classList.add('opacity-0');

            setTimeout(() => {
                mainImage.src = newSrc;
                mainImage.classList.remove('opacity-0');
                currentImageIndex = newIndex;

                const thumbnails = document.querySelectorAll('[data-src]');
                thumbnails.forEach(thumb => {
                    thumb.classList.toggle('border-blue-500', parseInt(thumb.dataset.index) ===
                        newIndex);
                    thumb.classList.toggle('border-transparent', parseInt(thumb.dataset.index) !==
                        newIndex);
                });

                const mobileIndicators = document.querySelectorAll('#mobileIndicators button');
                mobileIndicators.forEach(indicator => {
                    indicator.classList.toggle('bg-blue-600', parseInt(indicator.dataset.index) ===
                        newIndex);
                    indicator.classList.toggle('bg-gray-300', parseInt(indicator.dataset.index) !==
                        newIndex);
                });
            }, 300);
        };
    </script>
@endsection
