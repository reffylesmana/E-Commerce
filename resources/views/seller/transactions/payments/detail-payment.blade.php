@extends('layouts.seller')

@section('title', 'Detail Pembayaran - Seller Dashboard')

@section('content')
<div class="container mx-auto max-w-5xl py-8 px-4">
    <!-- Header -->
    <div class="relative pb-6 mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Pembayaran #{{ $payment->payment_id }}</h1>
        <p class="text-sm md:text-base text-gray-600 mt-1">Informasi lengkap tentang pembayaran</p>
        <div class="absolute bottom-0 left-0 w-16 h-1 bg-indigo-600 rounded-full"></div>
    </div>

    <!-- Payment Information -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 transition-all duration-300 hover:shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Status:</span>
                    <div class="mt-1">
                        @switch($payment->status)
                            @case('pending')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @break
                            @case('success')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Success</span>
                                @break
                            @case('failed')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                @break
                            @case('expired')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Expired</span>
                                @break
                            @case('refunded')
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Refunded</span>
                                @break
                            @default
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $payment->status }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Metode Pembayaran:</span>
                    <p class="text-gray-800 font-medium mt-1">
                        @switch($payment->payment_method)
                            @case('bank_transfer') Bank Transfer @break
                            @case('credit_card') Credit Card @break
                            @case('e_wallet') E-Wallet @break
                            @case('paylater') Paylater @break
                            @default {{ $payment->payment_method }}
                        @endswitch
                    </p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Jumlah:</span>
                    <p class="text-gray-800 font-medium mt-1">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Tanggal Pembayaran:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Pesanan:</span>
                    <p class="text-gray-800 font-medium mt-1">#{{ $payment->transaction->order->order_number }}</p>
                </div>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Nama Pelanggan:</span>
                    <p class="text-gray-800 font-medium mt-1">{{ $payment->transaction->order->user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Transaksi</h2>
        <div class="border-t border-gray-200 pt-4">
            <dl class="divide-y divide-gray-200">
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">ID Transaksi</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->transaction->id }}</dd>
                </div>
                @if($payment->payment_method == 'bank_transfer')
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Bank</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->bank_name ?? 'N/A' }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Nomor Rekening</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->account_number ?? 'N/A' }}</dd>
                </div>
                @endif
                @if($payment->payment_method == 'credit_card')
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Kartu</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->card_type ?? 'N/A' }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Nomor Kartu</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->masked_card ?? 'N/A' }}</dd>
                </div>
                @endif
                @if($payment->payment_method == 'e_wallet')
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Provider</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->wallet_provider ?? 'N/A' }}</dd>
                </div>
                @endif
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y, H:i') }}</dd>
                </div>
                @if($payment->paid_at)
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibayar</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->paid_at->format('d M Y, H:i') }}</dd>
                </div>
                @endif
                @if($payment->notes)
                <div class="py-3 flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                    <dd class="text-sm text-gray-900">{{ $payment->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
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
                            {{ $payment->transaction->order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->transaction->order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($payment->transaction->order->status)
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
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($payment->transaction->order->status) }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($payment->transaction->order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('seller.transactions.orders.show', $payment->transaction->order->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150">Detail</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Link -->
    <div>
        <a href="{{ route('seller.transactions.payments.payments') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Pembayaran
        </a>
    </div>
</div>
@endsection
