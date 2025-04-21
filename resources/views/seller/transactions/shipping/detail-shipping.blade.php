@extends('layouts.seller')

@section('title', 'Detail Pengiriman - Seller Dashboard')

@section('content')
<div class="container mx-auto max-w-5xl py-8 px-4">
    <!-- Header -->
    <div class="relative pb-6 mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pengiriman #{{ $shipping->no_resi ?: 'Belum ada nomor resi' }}</h1>
        <p class="text-sm md:text-base text-gray-600 mt-1">Informasi lengkap tentang pengiriman</p>
        <div class="absolute bottom-0 left-0 w-16 h-1 bg-indigo-600 rounded-full"></div>
    </div>

    <!-- Shipping Information -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 transition-all duration-300 hover:shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Status Pengiriman:</span>
                    <div class="mt-1">
                        @switch($shipping->status)
                            @case('pending')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @break
                            @case('shipped')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Shipped</span>
                                @break
                            @case('delivered')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Delivered</span>
                                @break
                            @case('failed')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                @break
                            @default
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $shipping->status }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Status Order:</span>
                    <div class="mt-1">
                        @switch($shipping->order->status)
                            @case('pending')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @break
                            @case('processing')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Processing</span>
                                @break
                            @case('shipped')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Shipped</span>
                                @break
                            @case('completed')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                @break
                            @case('cancelled')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                @break
                            @default
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $shipping->order->status }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Metode Pengiriman:</span>
                    <p class="text-gray-800 font-medium mt-1">
                        @switch($shipping->shipping_method)
                            @case('jne') JNE @break
                            @case('jnt') J&T @break
                            @case('pos') POS Indonesia @break
                            @case('sicepat') SiCepat @break
                            @case('anteraja') AnterAja @break
                            @default {{ $shipping->shipping_method }}
                        @endswitch
                    </p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Nomor Resi:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $shipping->no_resi ?: 'Belum ada' }}</p>
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Tanggal Dibuat:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $shipping->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Tanggal Dikirim:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $shipping->shipped_at ? $shipping->shipped_at->format('d M Y, H:i') : 'Belum dikirim' }}</p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Estimasi Tiba:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $shipping->estimated_arrival ? \Carbon\Carbon::parse($shipping->estimated_arrival)->format('d M Y, H:i') : 'Belum dikirim' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Details -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Pengiriman</h2>
        <div class="border-t border-gray-200 pt-4">
            <dl class="divide-y divide-gray-200">
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman</dt>
                    <dd class="text-sm text-gray-900">{{ $shipping->order->shipping_address }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Penerima</dt>
                    <dd class="text-sm text-gray-900">{{ $shipping->order->user->name }}</dd>
                </div>
                @if($shipping->notes)
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                    <dd class="text-sm text-gray-900">{{ $shipping->notes }}</dd>
                </div>
                @endif
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Biaya Pengiriman</dt>
                    <dd class="text-sm text-gray-900">Rp 20.000</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Tracking Timeline -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Pengiriman</h2>
        @if(isset($shipping->tracking_history) && count($shipping->tracking_history) > 0)
            <div class="relative">
                <div class="absolute top-0 bottom-0 left-6 w-0.5 bg-gray-200"></div>
                <ul class="space-y-6">
                    @foreach($shipping->tracking_history as $history)
                        <li class="relative pl-8">
                            <div class="absolute left-0 top-1 w-12 h-12 flex items-center justify-center">
                                <div class="w-3 h-3 bg-indigo-600 rounded-full"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-900">{{ $history->status }}</div>
                                <div class="text-xs text-gray-500">{{ $history->timestamp->format('d M Y, H:i') }}</div>
                                <div class="mt-2 text-sm text-gray-600">{{ $history->description }}</div>
                                <div class="text-xs text-gray-500">{{ $history->location }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-6 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0  00 2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-1">Belum ada riwayat pengiriman</p>
                <p class="text-gray-500">Informasi pengiriman akan muncul setelah paket dikirim.</p>
            </div>
        @endif
    </div>

    <!-- Related Order -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Terkait</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $shipping->order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $shipping->order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($shipping->order->status)
                                @case('pending')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @break
                                @case('processing')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Processing</span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Shipped</span>
                                    @break
                                @case('completed')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                    @break
                                @default
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($shipping->order->status) }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($shipping->order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('seller.transactions.orders.show', $shipping->order->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150">Detail</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Send Shipping Form (Only for Processing Orders) -->
    @if($shipping->order->status === 'processing')
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Kirim Pesanan</h2>
        <form action="{{ route('seller.transactions.shipping.send', $shipping->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">{{ $shipping->notes }}</textarea>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                    Kirim Pesanan
                </button>
            </div>
            <p class="text-sm text-gray-500 italic mt-2">
                Dengan mengklik tombol "Kirim Pesanan", status pesanan akan berubah menjadi "Shipped" dan tidak dapat diubah kembali.
            </p>
        </form>
    </div>
    @endif

    <!-- Back Link -->
    <div>
        <a href="{{ route('seller.transactions.shipping.shipping') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Pengiriman
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation before sending shipment
        const sendForm = document.querySelector('form[action*="send"]');
        if (sendForm) {
            sendForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting immediately
                const form = this; // Reference to the form

                // SweetAlert confirmation
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin mengirim pesanan ini? Status akan berubah menjadi "Shipped" dan tidak dapat diubah kembali.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });
        }
    });
</script>
@endsection