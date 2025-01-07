@extends('layouts.app')

@section('content')
    <div class="container-fluid mx-auto pb-5 mt-36 px-4 sm:px-8 lg:px-16"> 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Sidebar -->
            <div>
                <!-- Product Image Carousel -->
                <div class="mb-4 relative">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @foreach ($product->photos as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ $photo->photo ? Storage::url($photo->photo) : asset('default-image.jpg') }}"
                                        class="w-full h-auto max-h-96 object-contain rounded-lg" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                        <!-- Carousel Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                <!-- Thumbnail Images -->
                <div class="flex space-x-2 mt-4">
                    @foreach ($product->photos as $photo)
                        <img src="{{ $photo->photo ? Storage::url($photo->photo) : asset('default-image.jpg') }}"
                            alt="{{ $product->name }}"
                            class="w-20 h-20 object-cover border-2 border-transparent hover:border-gray-600 cursor-pointer">
                    @endforeach
                </div>
            </div>

            <!-- Product Details -->
            <div>
                <h5 class="text-sm text-gray-500 uppercase">{{ $product->category->name }}</h5>
                <h2 class="text-2xl font-semibold mt-2">{{ $product->name }}</h2>
                <p class="text-yellow-500 mt-2">
                    <span class="text-gray-700">Stok {{ $product->stock }} • </span>
                    <span class="text-gray-700">Terjual 16 •</span>
                    ★ ★ ★ ★ ☆ <span class="text-gray-400">(42 reviews)</span>
                </p>
                <h4 class="text-xl text-red-600 mt-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                <hr class="my-4">
                <h5 class="font-semibold mt-3">Detail</h5>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <dt class="font-medium">Ukuran</dt>
                    <dd>{{ $product->size }}</dd>
                    <dt class="font-medium">Berat</dt>
                    <dd>{{ $product->weight }}kg</dd>
                    <dt class="font-medium">Stok</dt>
                    <dd>{{ $product->stock }}</dd>
                </dl>

                <p class="mt-4 text-gray-600">{!! $product->description !!}</p>

                <!-- Add to Cart -->
                <div class="mt-6">
                    <form method="post" action="/carts/add/{{ $product->slug }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-primary-dark focus:outline-none transition">
                            <i class="bi bi-cart mr-2"></i> Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="container mx-auto mt-10 pb-5 px-4 sm:px-8 lg:px-16">
            <h2 class="text-xl font-semibold mb-4 text-center">Ulasan Produk</h2>
            <div class="space-y-6">
                <!-- Review Item -->
                <div class="flex items-start space-x-4 border-b pb-4">
                    <img src="{{ asset('default-avatar.jpg') }}" alt="User Avatar"
                        class="w-12 h-12 rounded-full object-cover border">
                    <div>
                        <h5 class="font-semibold text-lg">User 1</h5>
                        <p class="text-sm text-gray-600 mt-1">"Produk ini sangat bagus dan berkualitas tinggi!"</p>
                        <p class="text-yellow-500 mt-2">★ ★ ★ ★ ☆</p>
                    </div>
                </div>
                <!-- Add More Reviews -->
                <div class="text-center mt-4">
                    <a href="#" class="text-blue-500 hover:underline">Lihat Semua Ulasan</a>
                </div>
            </div>
        </div>

        <!-- Product Suggestions -->
        @if (count($productSuggets) > 0)
            <div class="container pb-5 mt-6 px-4 sm:px-8 lg:px-16">
                <hr>
                <h2 class="text-lg font-semibold mb-4">Produk Lainnya ({{ $product->category->name }})</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($productSuggets as $suggestedProduct)
                        <div class="group relative">
                            <a href="/products/{{ $suggestedProduct->slug }}" class="block">
                                <div
                                    class="border border-transparent bg-transparent rounded-lg shadow-md hover:shadow-lg transition">
                                    <img src="{{ $suggestedProduct->photos->first()?->photo ? Storage::url($suggestedProduct->photos->first()->photo) : asset('default-image.jpg') }}"
                                        class="w-full h-48 object-cover rounded-t-lg" alt="{{ $suggestedProduct->name }}">
                                    <div class="absolute top-2 right-2">
                                        <form method="post" action="/carts/add/{{ $suggestedProduct->slug }}">
                                            @csrf
                                            <button type="submit"
                                                class="bg-white rounded-full shadow-sm hover:bg-gray-100 p-2">
                                                <i class="bi bi-cart text-black"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-center py-4 px-2">
                                        <h6 class="font-semibold">{{ $suggestedProduct->name }}</h6>
                                        <p class="text-red-600">Rp
                                            {{ number_format($suggestedProduct->price, 0, ',', '.') }}</p>
                                        <p class="text-yellow-500">★ ★ ★ ★ ☆ <span class="text-gray-400">(42 reviews)</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
