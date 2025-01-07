@extends('layouts.app')

@section('content')
    <!-- Hero Section Start-->
    <section class="relative w-full pt-16">
        <div class="swiper">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide text-white flex justify-center items-center text-3xl">
                    <div class="text-center w-full px-4">
                        <!-- Image for sm screens -->
                        <img src="{{ asset('img/Banner4.png') }}" alt="Slide 1 - sm"
                            class="w-full h-auto max-h-[550px] object-contain block sm:hidden">
                        <!-- Image for md and lg screens -->
                        <img src="{{ asset('img/Banner1.png') }}" alt="Slide 1 - md/lg"
                            class="w-11/12 md:w-10/12 lg:w-4/5 h-auto max-h-[500px] object-contain mx-auto hidden sm:block">
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide text-white flex justify-center items-center text-3xl">
                    <div class="text-center w-full px-4">
                        <!-- Image for sm screens -->
                        <img src="{{ asset('img/Banner5.png') }}" alt="Slide 2 - sm"
                            class="w-full h-auto max-h-[550px] object-contain block sm:hidden">
                        <!-- Image for md and lg screens -->
                        <img src="{{ asset('img/Banner2.png') }}" alt="Slide 2 - md/lg"
                            class="w-11/12 md:w-10/12 lg:w-4/5 h-auto max-h-[500px] object-contain mx-auto hidden sm:block">
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide text-white flex justify-center items-center text-3xl">
                    <div class="text-center w-full px-4">
                        <!-- Image for sm screens -->
                        <img src="{{ asset('img/Banner6.png') }}" alt="Slide 3 - sm"
                            class="w-full h-auto max-h-[550px] object-contain block sm:hidden">
                        <!-- Image for md and lg screens -->
                        <img src="{{ asset('img/Banner3.png') }}" alt="Slide 3 - md/lg"
                            class="w-11/12 md:w-10/12 lg:w-4/5 h-auto max-h-[500px] object-contain mx-auto hidden sm:block">
                    </div>
                </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    
    
    
    <!-- Hero Section End-->

    <!-- Kategori Section Start-->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-xl font-semibold mb-4">
                KATEGORI
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($categories as $category)
                    <div
                        class="text-center relative border-b border-r bg-white shadow-sm hover:shadow-lg transform hover:scale-105 transition-all duration-300 ease-in-out rounded-lg">
                        <img src="{{ Storage::url($category->photo) }}" alt="{{ $category->name }}" class="mx-auto mb-2"
                            height="100" width="100">
                        <p class="font-medium text-gray-700">
                            {{ $category->name }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Kategori Section End-->
    <!-- Product Cards Section Start -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
                Barang-barang Terbaru dari TeknoShop
            </h1>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <div
                        class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out w-full">
                        <a href="/products/{{ $product->slug }}" class="block h-full">
                            <div class="card product-item border-0 bg-transparent relative h-full">
                                <img src="{{ $product->photos->first()?->photo ? Storage::url($product->photos->first()->photo) : asset('default-image.jpg') }}"
                                    class="w-full h-auto object-cover rounded-lg mx-auto mt-4" alt="{{ $product->name }}">
                                <!-- Konten Produk -->
                                <div class="card-body text-center mt-4 px-4 pb-4">
                                    <h6 class="text-[18px] font-semibold text-gray-800 truncate">{{ $product->name }}</h6>
                                    <p class="text-red-500 text-[16px] font-bold mb-2">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-yellow-500 text-[16px]">★ ★ ★ ★ ☆ <span
                                            class="text-gray-500 text-[14px]">(42 reviews)</span></p>
                                </div>
                                <div
                                    class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                                    <form method="post" action="/carts/add/{{ $product->slug }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-white text-gray-700 hover:bg-gray-200 rounded-full shadow-md p-3">
                                            <i class="bi bi-cart text-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Product Cards Section End -->
@endsection
