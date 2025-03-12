@extends('layouts.app')

@section('title', 'TechnoShop - Home')
@section('description', 'Discover the latest in technology with TechStore. Shop laptops, smartphones, accessories, and gaming gear.')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-4 py-24 sm:py-32 relative z-10">
            <div class="text-center" data-aos="fade-up">
                <h1 class="text-4xl sm:text-6xl font-extrabold mb-4 leading-tight animate-pulse">
                    Discover the Future of Tech
                </h1>
                <p class="text-xl sm:text-2xl mb-8 max-w-2xl mx-auto">
                    Incredible deals on the latest gadgets that will transform your digital life
                </p>
                <a href="/products" class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-blue-100 transition-colors duration-200 transform hover:scale-105 animate-bounce">
                    Shop Now
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-gray-50 dark:from-gray-900 to-transparent"></div>
    </section>

    <!-- Categories -->
    <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-8">
                @foreach(['Laptops', 'Smartphones', 'Accessories', 'Gaming'] as $index => $category)
                    <a href="#" class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl p-6 flex flex-col items-center transition duration-300 group-hover:bg-blue-100 dark:group-hover:bg-blue-900 transform group-hover:scale-105 group-hover:shadow-lg">
                            <div class="w-20 h-20 mb-4 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                {{ $category[0] }}
                            </div>
                            <h3 class="text-lg font-semibold group-hover:text-blue-600 dark:text-white">{{ $category }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Featured Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $index => $product)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <a href="{{ route('products.show', $product->id) }}" class="block h-full flex flex-col relative">
                            <div class="relative aspect-w-1 aspect-h-1 overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photos->first()->photo) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300"></div>
                            </div>
                            <div class="p-4 flex-grow flex flex-col relative z-10">
                                <h3 class="font-semibold text-lg mb-2 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">{{ $product->name }}</h3>
                                <div class="flex items-center mb-2">
                                    <div class="flex">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="h-4 w-4 {{ $star <= round($product->rating) ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400 text-xs">({{ number_format($product->rating, 1) }})</span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm line-clamp-3 flex-grow">{{ $product->description }}</p>
                                <div class="flex justify-between items-center mt-auto">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold text-lg">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    <button onclick="event.preventDefault(); event.stopPropagation();" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 text-sm transform group-hover:scale-105 group-hover:shadow-md">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                            <div class="absolute inset-0  opacity-0 group-hover:opacity-50 transition duration-300"></div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for($i = 1; $i <= 3; $i++)
                    <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="{{ ($i - 1) * 100 }}">
                        <div class="flex items-center mb-4">
                            <img src="#" alt="User" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h3 class="font-semibold dark:text-white">John Doe</h3>
                                <div class="flex">
                                    @for($star = 1; $star <= 5; $star++)
                                        <svg class="h-4 w-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">"Amazing products and excellent customer service. I'm a happy customer!"</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                @foreach([
                    ['🚚', 'Free Shipping'],
                    ['🔄', 'Easy Returns'],
                    ['✅', '100% Authentic'],
                    ['🔒', 'Secure Payment']
                ] as $index => $badge)
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
        <div class="container mx-auto px-4">
            <h2 class="text-3xl sm:text-4xl font-bold mb-12 text-center dark:text-white" data-aos="fade-up">Tech Blog</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach([
                    "Top 10 Laptops for 2025",
                    "The Future of Smartphones",
                    "Essential Tech Accessories"
                ] as $index => $title)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <img src="#" alt="{{ $title }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2 dark:text-white">{{ $title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            <a href="#" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Read More</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">About TechStore</h3>
                    <p class="text-gray-400">Empowering your digital lifestyle with cutting-edge technology and unparalleled customer experience.</p>
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
                        @foreach(['Facebook', 'Twitter', 'Instagram', 'LinkedIn'] as $social)
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">{{ $social }}</a>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Subscribe to our newsletter</h4>
                        <form class="flex">
                            <input type="email" placeholder="Your email" class="px-4 py-2 rounded-l-lg w-full">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-colors duration-200">Subscribe</button>
                        </form>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Payment Methods</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Visa', 'Mastercard', 'PayPal', 'Apple Pay'] as $method)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $method }}</div>
                        @endforeach
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-4">Shipping Partners</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['FedEx', 'UPS', 'DHL'] as $partner)
                            <div class="bg-gray-700 text-white px-3 py-1 rounded-full text-sm">{{ $partner }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} TechStore. All rights reserved. | <a href="#" class="hover:text-white">Privacy Policy</a> | <a href="#" class="hover:text-white">Terms of Service</a></p>
            </div>
        </div>
    </footer>
@endsection