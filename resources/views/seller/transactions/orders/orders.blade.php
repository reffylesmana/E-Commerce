@extends('layouts.seller')

@section('title', 'Pemesanan - Seller Dashboard')

@section('content')
<style>
    /* Modern dashboard styling */
    .page-container {
        min-height: 100vh;
        background-color: #f9fafb;
    }

    .page-header {
        position: relative;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        font-size: 1rem;
        color: #6b7280;
    }

    .page-title-underline {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 4rem;
        height: 0.25rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    }

    .table-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .table-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .table-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .table-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .products-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .products-table th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6b7280;
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .products-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .products-table tr:last-child td {
        border-bottom: none;
    }

    .products-table tr {
        transition: background-color 0.2s ease;
    }

    .products-table tr:hover {
        background-color: #f9fafb;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-inactive {
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .action-button-edit {
        color: #4f46e5;
    }

    .action-button-edit:hover {
        background-color: rgba(79, 70, 229, 0.05);
    }

    .action-button-delete {
        color: #ef4444;
    }

    .action-button-delete:hover {
        background-color: rgba(239, 68, 68, 0.05);
    }

    .table-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Empty state */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
    }

    .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        border-radius: 9999px;
        background-color: #f3f4f6;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-description {
        font-size: 0.875rem;
        color: #6b7280;
        max-width: 24rem;
        margin: 0 auto 1.5rem;
    }

 /* DataTables customization */
 .dataTables_wrapper {
        padding: 0 1.5rem;
    }

    .dataTables_info {
        padding: 1rem 1.5rem;
        color: #6b7280;
    }

    .dataTables_wrapper {
        padding: 0 1.5rem;
    }

    .dataTables_info {
        padding: 1rem 1.5rem;
        color: #6b7280;
    }

    .dataTables_paginate {
        margin-top: 1rem;
        text-align: right;
    }

    .dataTables_paginate .paginate_button {
        padding: 0.5rem 1rem;
        margin: 0 0.1rem;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        background: white;
        color: #4f46e5;
        transition: background-color 0.2s ease, color 0.2s ease;
        display: inline-block;
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
    }

    .dataTables_paginate .paginate_button.current {
        background-color: #4f46e5;
        color: white;
        border: 1px solid #4f46e5;
    }

    .dataTables_length select {
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        padding: 0.25rem;
    }

    .dataTables_filter input {
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
        padding: 0.25rem;
        margin-left: 0.5rem;
    }
</style>

<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="page-header">
                <h1 class="page-title">Pemesanan</h1>
                <p class="page-subtitle">Kelola semua pemesanan Anda di satu tempat</p>
                <div class="page-title-underline"></div>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('seller.transactions.orders.orders') }}" id="filterForm" class="mb-6">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex flex-col md:flex-row gap-4 justify-between">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="date" name="date" value="{{ request('date') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="hidden">Submit</button>
        </form>

        <!-- Orders Table -->
        <div class="table-card animate-fade-in">
            <div class="table-header">
                <h2 class="table-title">Daftar Pemesanan</h2>
                <p class="table-subtitle">Kelola semua pemesanan Anda</p>
            </div>
            <div class="table-container">
                <table class="products-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @switch($order->status)
                                    @case('pending')
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                        @break
                                    @case('processing')
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-blue-100 text-blue-800">Processing</span>
                                        @break
                                    @case('shipped')
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-purple-100 text-purple-800">Shipped</span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-green-100 text-green-800">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-red-100 text-red-800">Cancelled</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 rounded text-sm font-medium bg-gray-100 text-gray-700">{{ ucfirst($order->status) }}</span>
                                @endswitch                                
                                </td>
                                <td>
                                    <a href="{{ route('seller.transactions.orders.show', $order->id) }}"
                                        class="action-button action-button-edit">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="iconify text-3xl" data-icon="tabler:box-off"></i>
                                    </div>
                                    <h3 class="empty-title">Tidak ada pemesanan</h3>
                                    <p class="empty-description">Belum ada pemesanan yang dilakukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('status').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('date').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#orders-table').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            order: [[1, 'desc']],
        });
    });
</script>
@endsection