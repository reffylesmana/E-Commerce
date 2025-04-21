@extends('layouts.seller')

@section('title', 'Detail Pesanan - Seller Dashboard')

@section('content')
    <div class="container mx-auto max-w-5xl py-8 px-4">
        <!-- Header -->
        <div class="relative pb-6 mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-sm md:text-base text-gray-600 mt-1">Rincian lengkap dari pesanan Anda</p>
            <div class="absolute bottom-0 left-0 w-16 h-1 bg-indigo-600 rounded-full"></div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 transition-all duration-300 hover:shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Status:</span>
                        <div class="mt-1">
                            @switch($order->status)
                                @case('pending')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @break

                                @case('processing')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Processing</span>
                                @break

                                @case('shipped')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Shipped</span>
                                @break

                                @case('completed')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                @break

                                @case('cancelled')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                @break

                                @default
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->status) }}</span>
                            @endswitch
                        </div>
                    </div>
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Nama Pelanggan:</span>
                        <p class="text-gray-800 font-medium mt-1">{{ $order->user->name }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Tanggal Pesanan:</span>
                        <p class="text-gray-800 font-medium mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Alamat Pengiriman:</span>
                        <p class="text-gray-800 font-medium mt-1">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Item Pesanan</h2>
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->orderItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                                            @php
                                                $photo = optional($item->product->productPhotos)->first();
                                            @endphp
                                            @if ($item->product->photos->first())
                                                <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}"
                                                    alt="Foto Produk" class="object-cover w-full h-full">
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            @if ($item->product->sku)
                                                <div class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</div>
                                            @endif
                                        </div>
                                    </div>

                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Subtotal:</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($order->subtotal ?? $order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @if (isset($order->shipping_cost) && $order->shipping_cost > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Biaya
                                    Pengiriman:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        @if (isset($order->tax) && $order->tax > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Pajak:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->tax, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-base font-bold text-gray-900 text-right">Total:</td>
                            <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Back Link -->
        <div>
            <a href="{{ route('seller.transactions.orders.orders') }}"
                class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    <script>
        // Add any JavaScript for interactivity here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Confirmation before status update
            const statusForm = document.querySelector('form[action*="update-status"]');
            if (statusForm) {
                statusForm.addEventListener('submit', function(e) {
                    const status = document.getElementById('status').value;
                    if (status === 'cancelled') {
                        if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
@endsection
