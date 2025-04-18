@extends('layouts.seller')

@section('title', 'Detail Pembayaran - Seller Dashboard')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Pembayaran #{{ $payment->payment_id }}</h2>

    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <p class="text-sm text-gray-600">Status: <span class="font-medium text-gray-800">{{ $payment->status }}</span></p>
        <p class="text-sm text-gray-600">Metode Pembayaran: <span class="font-medium text-gray-800">{{ $payment->payment_method }}</span></p>
        <p class="text-sm text-gray-600">Jumlah: <span class="font-medium text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></p>
        <p class="text-sm text-gray-600">Pesanan: <span class="font-medium text-gray-800">#{{ $payment->transaction->order->order_number }}</span></p>
        <p class="text-sm text-gray-600">Nama Pelanggan: <span class="font-medium text-gray-800">{{ $payment->transaction->order->user->name }}</span></p>
    </div>

    <div class="mt-6">
        <a href="{{ route('transactions.payments') }}" class="text-indigo-600 hover:text-indigo-900">Kembali ke Daftar Pembayaran</a>
    </div>
</div>
@endsection