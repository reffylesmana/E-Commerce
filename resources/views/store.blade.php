@extends('layouts.app')

@section('title', $store->name)
@section('description', 'Temukan produk berkualitas dari ' . $store->name)

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="relative rounded-xl overflow-hidden mb-8 bg-gradient-to-r from-blue-600 to-purple-600">
                <div class="absolute inset-0 bg-black opacity-30"></div>
                <div class="relative z-10 py-12 px-6 md:px-12 flex items-center">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-20 h-20 rounded-full border-4 border-white mr-6 object-cover shadow-lg">
                    @endif
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $store->name }}</h1>
                        <div class="flex items-center mb-4">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= round($store->rating ?? 4) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-white text-sm">({{ number_format($store->rating ?? 4, 1) }})</span>
                            @if($store->is_official ?? false)
                                <span class="ml-4 px-2 py-0.5 text-xs font-medium bg-blue-500 text-white rounded-full">Official Store</span>
                            @endif
                        </div>
                        <p class="text-white text-opacity-90 max-w-xl">{{ $store->description ?? 'Temukan produk berkualitas dari toko ini.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Store Info Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-8 transform transition-all duration-300 hover:shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bergabung Sejak</p>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ isset($store->created_at) ? $store->created_at->format('d M Y') : now()->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Produk</p>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $products->total() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pelanggan</p>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $store->customers_count ?? rand(100, 5000) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Produk Toko</h2>
                    <div class="flex space-x-2">
                        <select id="sortProducts" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="latest">Terbaru</option>
                            <option value="price_low">Harga Terendah</option>
                            <option value="price_high">Harga Tertinggi</option>
                            <option value="popular">Terpopuler</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
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
                                                class="bg-white text-gray-800 p-2 rounded-full hover:bg-red-600 hover:text-white transition-colors duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </button>
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
                
                <!-- Empty State -->
                @if($products->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Tidak ada produk yang ditemukan</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Toko ini belum menambahkan produk apapun</p>
                        <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition-colors duration-300">
                            Kembali ke Beranda
                        </a>
                    </div>
                @endif
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sort products functionality
            const sortSelect = document.getElementById('sortProducts');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    window.location.href = `{{ route('store', $store->slug) }}?sort=${this.value}`;
                });
            }
            
            // Display SweetAlert notifications for flash messages
            @if(session('success'))
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
            
            @if(session('error'))
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
    </script>
@endsection

