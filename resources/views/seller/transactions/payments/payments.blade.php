@extends('layouts.seller')

@section('title', 'Pembayaran - Seller Dashboard')

@section('content')
<style>
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

    /* Stats cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .stat-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        opacity: 0.05;
        transform: translate(30%, -30%);
        transition: all 0.3s ease;
    }

    .stat-card:hover::after {
        transform: translate(25%, -25%) scale(1.1);
        opacity: 0.1;
    }

    .stat-card.blue::after {
        background-color: #4f46e5;
    }

    .stat-card.green::after {
        background-color: #10b981;
    }

    .stat-card.yellow::after {
        background-color: #f59e0b;
    }

    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
    }

    .stat-icon.blue {
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
    }

    .stat-icon.green {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-icon.yellow {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }

    /* Table styling */
    .table-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
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
    }

    .products-table tr:last-child td {
        border-bottom: none;
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

    .badge-green {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-yellow {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .badge-red {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-gray {
        background-color: rgba(156, 163, 175, 0.1);
        color: #6b7280;
    }

    /* Empty State Styling */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
    }

    .empty-icon {
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .empty-description {
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin-left: 0.25rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4f46e5;
        color: white !important;
        border-color: #4f46e5;
    }
</style>
<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="page-header">
                <h1 class="page-title">Pembayaran</h1>
                <p class="page-subtitle">Kelola semua pembayaran toko Anda di satu tempat</p>
                <div class="page-title-underline"></div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid mb-8">
            <!-- Total Payments Card -->
            <div class="stat-card green animate-fade-in">
                <div class="stat-icon green">
                    <i class="iconify text-2xl" data-icon="heroicons:banknotes"></i>
                </div>
                <div class="stat-label">Total Pembayaran</div>
                <div class="stat-value">Rp {{ number_format($totalPayments ?? 0, 0, ',', '.') }}</div>
            </div>

            <!-- Successful Payments Card -->
            <div class="stat-card blue animate-fade-in-delay-1">
                <div class="stat-icon blue">
                    <i class="iconify text-2xl" data-icon="heroicons:check-circle"></i>
                </div>
                <div class="stat-label">Pembayaran Sukses</div>
                <div class="stat-value">{{ $successCount ?? 0 }}</div>
            </div>

            <!-- Pending Payments Card -->
            <div class="stat-card yellow animate-fade-in-delay-2">
                <div class="stat-icon yellow">
                    <i class="iconify text-2xl" data-icon="heroicons:clock"></i>
                </div>
                <div class="stat-label">Pembayaran Pending</div>
                <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
            </div>
        </div>

        <!-- Filter  -->
        <div class="table-card animate-fade-in mb-8">
            <div class="table-header">
            </div>
            <div class="flex flex-wrap gap-4 p-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="search-input">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment_method" name="payment_method" class="search-input">
                        <option value="">Semua Metode</option>
                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="paylater" {{ request('payment_method') === 'paylater' ? 'selected' : '' }}>Paylater</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="table-card animate-fade-in">
            <div class="table-container">
                <table class="products-table stripe hover" id="paymentsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Order</th>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments ?? [] as $payment)
                        <tr>
                            <td>{{ $payment->payment_id }}</td>
                            <td>{{ $payment->transaction->order->order_number }}</td>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @switch($payment->payment_method)
                                    @case('bank_transfer') Bank Transfer @break
                                    @case('credit_card') Credit Card @break
                                    @case('e_wallet') E-Wallet @break
                                    @case('paylater') Paylater @break
                                    @default {{ $payment->payment_method }}
                                @endswitch
                            </td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @switch($payment->status)
                                    @case('pending') <span class="badge badge-yellow">Pending</span> @break
                                    @case('success') <span class="badge badge-green">Success</span> @break
                                    @case('failed') <span class="badge badge-red">Failed</span> @break
                                    @case('expired') <span class="badge badge-gray">Expired</span> @break
                                    @case('refunded') <span class="badge badge-blue">Refunded</span> @break
                                    @default <span class="badge badge-gray">{{ $payment->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('seller.transactions.payments.show', $payment->id) }}" class="action-button action-button-edit">
                                    <i class="iconify mr-1" data-icon="tabler:eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="iconify text-3xl" data-icon="tabler:box-off"></i>
                                    </div>
                                    <h3 class="empty-title">Tidak ada data pembayaran</h3>
                                    <p class="empty-description">Belum ada riwayat pembayaran yang dapat ditampilkan.</p>
                                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#paymentsTable').DataTable({
            dom: '<"flex flex-wrap justify-between items-center mb-4"<"flex"l><"flex"f>><"table-responsive"t><"flex flex-wrap justify-between items-center mt-4"<"flex"i><"flex"p>>',
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data yang ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            columnDefs: [
                { orderable: true, targets: [0, 1, 2, 3, 4, 5] },
                { orderable: false, targets: [6] }
            ],
            order: [[2, 'desc']] // Default sort by date descending
        });

        // Filter functionality
        const statusFilter = document.getElementById('status');
        const methodFilter = document.getElementById('payment_method');
        
        [statusFilter, methodFilter].forEach(element => {
            element.addEventListener('change', function() {
                const table = $('#paymentsTable').DataTable();
                table.draw();
            });
        });

        // Custom filtering function
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                const status = statusFilter.value;
                const method = methodFilter.value;
                
                const rowStatus = data[5].toLowerCase();
                const rowMethod = data[3].toLowerCase();
                
                // Status filter
                if (status && !rowStatus.includes(status.toLowerCase())) {
                    return false;
                }
                
                // Method filter
                if (method) {
                    if (method === 'bank_transfer' && !rowMethod.includes('bank transfer')) {
                        return false;
                    }
                    if (method === 'credit_card' && !rowMethod.includes('credit card')) {
                        return false;
                    }
                    if (method === 'e_wallet' && !rowMethod.includes('e-wallet')) {
                        return false;
                    }
                    if (method === 'paylater' && !rowMethod.includes('paylater')) {
                        return false;
                    }
                }
                
                return true;
            }
        );
    });
</script>
@endsection