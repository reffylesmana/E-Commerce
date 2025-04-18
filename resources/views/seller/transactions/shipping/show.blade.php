@extends('layouts.seller')

@section('title', 'Detail Pengiriman - Seller Dashboard')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Detail Pengiriman #{{ $shipping->tracking_number }}</h2>

    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <p class="text-sm text-gray-600">Status: <span class="font-medium text-gray-800">{{ $shipping->status }}</span></p>
        <p class="text-sm text-gray-600">Metode Pengiriman: <span class="font-medium text-gray-800">{{ $shipping->shipping_method }}</span></p>
        <p class="text-sm text-gray-600">Pesanan: <span class="font-medium text-gray-800">#{{ $shipping->order->order_number }}</span></p>
        <p class="text-sm text-gray-600">Nama Pelanggan: <span class="font-medium text-gray-800">{{ $shipping->order->user->name }}</span></p>
    </div>

    <div class="mt-6">
        <a href="{{ route('seller.transactions.shipping.index') }}" class="text-indigo-600 hover:text-indigo-900">Kembali ke Daftar Pengiriman</a>
    </div>
</div>
@endsection