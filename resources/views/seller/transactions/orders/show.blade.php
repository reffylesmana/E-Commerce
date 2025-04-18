@extends('layouts.seller')

@section('title', 'Detail Pesanan - Seller Dashboard')

@section('content')
<style>
    /* Styling for the detail order page */
    .detail-container {
        background-color: #f9fafb;
        padding: 2rem 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }

    .detail-header {
        margin-bottom: 1.5rem;
    }

    .detail-header-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
    }

    .detail-header-subtitle {
        font-size: 1rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .detail-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }

    .info-card {
        padding: 1.5rem;
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .info-item {
        margin-bottom: 0.75rem;
    }

    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
    }

    .table-wrapper {
        background-color: white;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background-color: #f3f4f6;
    }

    .table-row {
        transition: background-color 0.2s ease;
    }

    .table-row:hover {
        background-color: #f9fafb;
    }

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4f46e5;
        transition: color 0.2s ease;
    }

    .back-link:hover {
        color: #4338ca;
    }
</style>

<div class="container mx-auto max-w-5xl py-8 px-4">
    <!-- Header -->
    <div class="detail-header">
        <h1 class="detail-header-title">Detail Pesanan #{{ $order->id }}</h1>
        <p class="detail-header-subtitle">Rincian lengkap dari pesanan Anda</p>
    </div>

    <!-- Order Information -->
    <div class="info-card">
        <div class="info-item">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Nama Pelanggan:</span>
            <span class="info-value">{{ $order->user->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Alamat Pengiriman:</span>
            <span class="info-value">{{ $order->shipping_address }}</span>
        </div>
    </div>

    <!-- Order Items -->
    <h2 class="detail-section-title">Item Pesanan</h2>
    <div class="table-wrapper">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Back Link -->
    <div>
        <a href="{{ route('seller.transactions.orders.orders') }}" class="back-link">Kembali ke Daftar Pesanan</a>
    </div>
</div>
@endsection