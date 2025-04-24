@extends('layouts.app')

@section('title', 'Checkout - TechnoShop')
@section('description', 'Selesaikan pembelian Anda dengan aman dan cepat')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('carts.index') }}"
                                class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Keranjang
                                Belanja</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Checkout</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Checkout</h1>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Checkout Form Section -->
                <div class="lg:w-2/3">
                    <form id="payment-form" action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <!-- Shipping Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Informasi Pengiriman
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                                            Lengkap</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ auth()->user()->name }}" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <input type="email" id="email" name="email"
                                            value="{{ auth()->user()->email }}" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                            Telepon</label>
                                        <input type="tel" id="phone" name="phone"
                                            value="{{ auth()->user()->phone ?? '' }}" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label for="city"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kota</label>
                                        <input type="text" id="city" name="city" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat
                                            Lengkap</label>
                                        <textarea id="address" name="address" rows="3" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ auth()->user()->address ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label for="address_type"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe
                                            Alamat</label>
                                        <select id="address_type" name="address_type" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">-- Pilih Tipe Alamat --</option>
                                            <option value="rumah">Rumah</option>
                                            <option value="kantor">Kantor</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="postal_code"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode
                                            Pos</label>
                                        <input type="text" id="postal_code" name="postal_code" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <label for="shipping_method"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode
                                            Pengiriman</label>
                                        <select id="shipping_method" name="shipping_method" required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            <option value="jne">JNE Regular (2-3 hari)</option>
                                            <option value="jnt">J&T Express (1-2 hari)</option>
                                            <option value="sicepat">SiCepat (1-2 hari)</option>
                                            <option value="anteraja">AnterAja (2-3 hari)</option>
                                            <option value="pos">POS Indonesia (3-7 hari)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Order Notes -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Catatan Pesanan
                                </h2>

                                <div>
                                    <label for="notes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan
                                        Tambahan (Opsional)</label>
                                    <textarea id="notes" name="notes" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Tambahkan catatan untuk pesanan Anda..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for Midtrans -->
                        <input type="hidden" name="transaction_id" id="transaction_id">
                        <input type="hidden" name="order_id" id="order_id"
                            value="ORDER-{{ time() }}-{{ auth()->id() }}">
                        <input type="hidden" name="gross_amount" id="gross_amount" value="{{ $total }}">
                        <input type="hidden" name="snap_token" id="snap_token">
                    </form>
                </div>

                <!-- Order Summary Section -->
                <div class="lg:w-1/3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden sticky top-24">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Ringkasan Pesanan
                            </h2>

                            <!-- Order Items -->
                            <div class="space-y-4 mb-6">
                                @foreach ($cartItems as $item)
                                    <div class="flex items-start gap-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                                        <div
                                            class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-800 dark:text-white truncate">
                                                {{ $item->product->name }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->quantity }} x Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-800 dark:text-white">
                                            Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Summary Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span id="subtotal" class="text-gray-800 dark:text-white font-medium">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pengiriman</span>
                                    <span id="shipping" class="text-gray-800 dark:text-white font-medium">
                                        Rp {{ number_format($shipping, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pajak (11%)</span>
                                    <span id="tax" class="text-gray-800 dark:text-white font-medium">
                                        Rp {{ number_format($tax, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 flex justify-between">
                                    <span class="text-lg font-semibold text-gray-800 dark:text-white">Total</span>
                                    <span id="total" class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button id="pay-button" type="button"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors duration-300 flex items-center justify-center font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                                Bayar Sekarang
                            </button>

                            <!-- Terms and Conditions -->
                            <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
                                <p>Dengan melakukan pembayaran, Anda menyetujui <a href="#"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">Syarat dan Ketentuan</a>
                                    serta <a href="#"
                                        class="text-blue-600 dark:text-blue-400 hover:underline">Kebijakan Privasi</a>
                                    kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const paymentForm = document.getElementById('payment-form');

            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Validasi form
                if (!paymentForm.checkValidity()) {
                    paymentForm.reportValidity();
                    return;
                }

                // Tampilkan status loading
                payButton.disabled = true;
                payButton.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sedang Memproses...';

                // Mendapatkan token Midtrans
                fetch('{{ route('checkout.getToken') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order_id: document.getElementById('order_id').value,
                            gross_amount: parseInt(document.getElementById('gross_amount')
                                .value),
                            customer_details: {
                                first_name: document.getElementById('name').value,
                                email: document.getElementById('email').value,
                                phone: document.getElementById('phone').value,
                                billing_address: {
                                    address: document.getElementById('address').value,
                                    city: document.getElementById('city').value,
                                    postal_code: document.getElementById('postal_code').value
                                }
                            }
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Error: ' + data.error);
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                            return;
                        }

                        // Buka jendela pembayaran Midtrans
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                document.getElementById('transaction_id').value = result
                                    .transaction_id;
                                document.getElementById('payment-form').submit();
                            },
                            onPending: function(result) {
                                document.getElementById('transaction_id').value = result
                                    .transaction_id;
                                document.getElementById('payment-form').submit();
                            },
                            onError: function(result) {
                                alert('Pembayaran Gagal: ' + result.status_message);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Sekarang';
                        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi nanti.');
                    });
            });
        });
    </script>
@endsection
