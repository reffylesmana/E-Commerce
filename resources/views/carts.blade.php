@extends('layouts.app')

@section('title', 'Keranjang Belanja - TechnoShop')
@section('description', 'Kelola keranjang belanja Anda dan lanjutkan ke checkout')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">
                            Keranjang Belanja
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Keranjang Belanja</h1>

        <div id="cart-container" class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items Section -->
            <div class="lg:w-2/3">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Produk dalam Keranjang</h2>
                            <span id="cart-count" class="bg-blue-600 text-white text-xs font-medium px-2.5 py-1 rounded-full">
                                {{ $cartItems->count() }} item
                            </span>
                        </div>

                        <!-- Cart Items List -->
                        <div id="cart-items" class="divide-y divide-gray-200 dark:divide-gray-700 {{ $cartItems->count() === 0 ? 'hidden' : '' }}">
                            @foreach($cartItems as $item)
                            <div class="cart-item py-6 first:pt-0 last:pb-0" data-id="{{ $item->id }}" data-price="{{ $item->product->price }}">
                                <div class="flex flex-col sm:flex-row">
                                    <div class="sm:w-24 sm:h-24 h-20 w-20 mb-4 sm:mb-0 relative">
                                        <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-contain rounded-lg">
                                    </div>
                                    <div class="flex-1 sm:ml-6 flex flex-col">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                    <span class="mr-2">Kategori: {{ $item->product->category->name ?? 'Uncategorized' }}</span>
                                                    <span>SKU: {{ $item->product->slug ?? 'N/A' }}</span>
                                                </p>
                                            </div>
                                            <div class="mt-2 sm:mt-0">
                                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-auto flex flex-col sm:flex-row sm:justify-between sm:items-center pt-4">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                <button type="button" onclick="decrementQuantity(this)" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white w-8 h-8 rounded-l-md flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input type="number" name="quantity" class="quantity-input w-12 h-8 text-center border-y border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:outline-none" value="{{ $item->quantity }}" min="1" max="99">
                                                <button type="button" onclick="incrementQuantity(this)" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white w-8 h-8 rounded-r-md flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                                <button type="submit" class="ml-4 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                    Update
                                                </button>
                                            </form>
                                            <div class="flex items-center mt-4 sm:mt-0">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline-block mr-4">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')" class="text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 text-sm flex items-center transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Empty Cart State -->
                        <div id="empty-cart" class="{{ $cartItems->count() > 0 ? 'hidden' : '' }} text-center py-12">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Keranjang Belanja Kosong</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Anda belum menambahkan produk apapun ke keranjang</p>
                            <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-colors duration-300">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Continue Shopping & Clear Cart -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Lanjutkan Belanja
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?')" class="inline-flex items-center text-red-600 dark:text-red-400 hover:underline {{ $cartItems->count() === 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $cartItems->count() === 0 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="lg:w-1/3">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Ringkasan Pesanan</h2>
                        
                        <!-- Order Summary Details -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span id="subtotal" class="text-gray-800 dark:text-white font-medium">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Pengiriman</span>
                                <span id="shipping" class="text-gray-800 dark:text-white font-medium">
                                    Rp {{ number_format($shipping, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Pajak (11%)</span>
                                <span id="tax" class="text-gray-800 dark:text-white font-medium">
                                    Rp {{ number_format($tax, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <form action="{{ route('cart.applyDiscount') }}" method="POST">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="text" 
                                            name="code" 
                                            placeholder="Masukkan kode diskon"
                                            class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <button type="submit" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            Terapkan
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Tampilkan Diskon Aktif -->
                                @if($discount)
                                    <div class="mt-4 flex justify-between items-center bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                                        <div>
                                            <span class="font-medium text-green-700 dark:text-green-300">
                                                {{ $discount['name'] }} ({{ $discount['code'] }})
                                            </span>
                                            <form action="{{ route('cart.removeDiscount') }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                <button type="submit" class="text-green-700 dark:text-green-300 hover:text-green-800 dark:hover:text-green-400 text-sm">
                                                    [Hapus]
                                                </button>
                                            </form>
                                        </div>
                                        <span class="text-green-700 dark:text-green-300 font-medium">
                                            @if($discount['type'] === 'percentage')
                                                -{{ $discount['value'] }}%
                                            @else
                                                -Rp {{ number_format($discount['value'], 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        
                            <!-- Hitungan Diskon -->
                            @if($discount)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Diskon</span>
                                    <span class="text-red-600 dark:text-red-400 font-medium">
                                        -Rp {{ number_format($discountValue, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between">
                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Total</span>
                                <span id="total" class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div> 
                        
                        <!-- Checkout Button -->
                        <button id="checkout-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors duration-300 flex items-center justify-center {{ $cartItems->count() === 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $cartItems->count() === 0 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            Lanjutkan ke Pembayaran
                        </button>
                        
                        <!-- Payment Methods -->
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Metode Pembayaran yang Tersedia:</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs px-2 py-1 rounded-full">Transfer Bank</span>
                                <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs px-2 py-1 rounded-full">QRIS</span>
                                <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs px-2 py-1 rounded-full">E-Wallet</span>
                                <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs px-2 py-1 rounded-full">Kartu Kredit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to increment quantity
    function incrementQuantity(button) {
        const input = button.previousElementSibling;
        const currentValue = parseInt(input.value);
        if (currentValue < 99) {
            input.value = currentValue + 1;
        }
    }
    
    // Function to decrement quantity
    function decrementQuantity(button) {
        const input = button.nextElementSibling;
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Replace regular confirms with SweetAlert
        const deleteButtons = document.querySelectorAll('button[onclick*="return confirm"]');
        deleteButtons.forEach(button => {
            const confirmMessage = button.getAttribute('onclick').match(/'([^']+)'/)[1];
            button.setAttribute('onclick', `confirmDelete(event, '${confirmMessage}')`);
        });
        
        // Checkout button
        const checkoutButton = document.getElementById('checkout-button');
        if (checkoutButton) {
            checkoutButton.addEventListener('click', function() {
                Swal.fire({
                    title: 'Lanjutkan ke Pembayaran',
                    text: 'Anda akan diarahkan ke halaman pembayaran',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to checkout page
                        window.location.href = '/checkout';
                    }
                });
            });
        }
    });
</script>
@endsection

