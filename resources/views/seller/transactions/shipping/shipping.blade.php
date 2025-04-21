@extends('layouts.seller')

@section('title', 'Pengiriman - Seller Dashboard')

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
            grid-template-columns: repeat(4, 1fr);
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
    
    .stat-card.yellow::after {
        background-color: #f59e0b;
    }
    
    .stat-card.blue::after {
        background-color: #3b82f6;
    }
    
    .stat-card.purple::after {
        background-color: #8b5cf6;
    }
    
    .stat-card.green::after {
        background-color: #10b981;
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
    
    .stat-icon.yellow {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .stat-icon.blue {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .stat-icon.purple {
        background-color: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    
    .stat-icon.green {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
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
        margin-bottom: 1.5rem;
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
    
    .search-container {
        position: relative;
        width: 100%;
        max-width: 20rem;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .search-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    
    .search-icon {
        position: absolute;
        top: 50%;
        left: 0.75rem;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .data-table th {
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
    
    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .data-table tr:last-child td {
        border-bottom: none;
    }
    
    .data-table tr {
        transition: background-color 0.2s ease;
    }
    
    .data-table tr:hover {
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
    
    .badge-yellow {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .badge-blue {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .badge-purple {
        background-color: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    
    .badge-green {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .badge-red {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .badge-gray {
        background-color: rgba(107, 114, 128, 0.1);
        color: #6b7280;
    }
    
    .table-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    /* Filter section */
    .filter-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1rem;
    }
    
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
    }
    
    @media (min-width: 640px) {
        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .filter-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .filter-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .filter-select, .filter-input {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .filter-select:focus, .filter-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    
    .filter-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1rem;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
    }
    
    .filter-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2), 0 4px 6px -2px rgba(79, 70, 229, 0.1);
    }
    
    .filter-button:active {
        transform: translateY(0);
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
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    .animate-fade-in-delay-1 {
        animation: fadeIn 0.3s ease-out 0.1s forwards;
        opacity: 0;
    }
    
    .animate-fade-in-delay-2 {
        animation: fadeIn 0.3s ease-out 0.2s forwards;
        opacity: 0;
    }
    
    .animate-fade-in-delay-3 {
        animation: fadeIn 0.3s ease-out 0.3s forwards;
        opacity: 0;
    }
    
    /* Action buttons */
    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .action-button:focus {
        outline: none;
    }
    
    .action-button-primary {
        color: #4f46e5;
    }
    
    .action-button-primary:hover {
        background-color: rgba(79, 70, 229, 0.05);
    }
    
    .action-button-success {
        color: #10b981;
    }
    
    .action-button-success:hover {
        background-color: rgba(16, 185, 129, 0.05);
    }
    
    /* Modal styling */
    .modal {
        position: fixed;
        inset: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(0, 0, 0, 0.5);
        transition: opacity 0.2s ease;
    }
    
    .modal-hidden {
        opacity: 0;
        pointer-events: none;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 100%;
        max-width: 28rem;
        margin: 0 1rem;
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    
    .modal-button-cancel {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .modal-button-cancel:hover {
        color: #111827;
        background-color: #f3f4f6;
    }
    
    .modal-button-submit {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: white;
        background-color: #10b981;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .modal-button-submit:hover {
        background-color: #059669;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input, .form-textarea {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .form-input:focus, .form-textarea:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    
    .form-textarea {
        min-height: 6rem;
        resize: vertical;
    }
    
    .form-hint {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        font-style: italic;
        color: #6b7280;
    }
</style>

<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Pengiriman</h1>
            <p class="page-subtitle">Kelola semua pengiriman toko Anda di satu tempat</p>
            <div class="page-title-underline"></div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid mb-8">
            <div class="stat-card yellow animate-fade-in">
                <div class="stat-icon yellow">
                    <i class="iconify text-2xl" data-icon="tabler:clock"></i>
                </div>
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
            </div>
            
            <div class="stat-card blue animate-fade-in-delay-1">
                <div class="stat-icon blue">
                    <i class="iconify text-2xl" data-icon="tabler:package"></i>
                </div>
                <div class="stat-label">Shipped</div>
                <div class="stat-value">{{ $shippedCount ?? 0 }}</div>
            </div>
            
            <div class="stat-card green animate-fade-in-delay-3">
                <div class="stat-icon green">
                    <i class="iconify text-2xl" data-icon="tabler:check"></i>
                </div>
                <div class="stat-label">Delivered</div>
                <div class="stat-value">{{ $deliveredCount ?? 0 }}</div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card animate-fade-in">
            <h2 class="filter-title">Filter Pengiriman</h2>
            <form method="GET" action="{{ route('seller.transactions.shipping.shipping') }}" id="filterForm">
                <div class="filter-grid">
                    <div>
                        <label for="status" class="filter-label">Status</label>
                        <select id="status" name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div>
                        <label for="shipping_method" class="filter-label">Kurir</label>
                        <select id="shipping_method" name="shipping_method" class="filter-select">
                            <option value="">Semua Kurir</option>
                            <option value="jne" {{ request('shipping_method') == 'jne' ? 'selected' : '' }}>JNE</option>
                            <option value="jnt" {{ request('shipping_method') == 'jnt' ? 'selected' : '' }}>J&T</option>
                            <option value="pos" {{ request('shipping_method') == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                            <option value="sicepat" {{ request('shipping_method') == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                            <option value="anteraja" {{ request('shipping_method') == 'anteraja' ? 'selected' : '' }}>AnterAja</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" class="filter-label">Cari Pengiriman</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" placeholder="Cari nomor resi..." value="{{ request('search') }}" class="filter-input pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="iconify text-gray-400" data-icon="tabler:search"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="filter-button">
                        <i class="iconify mr-1" data-icon="tabler:filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Shipping Table -->
        <div class="table-card animate-fade-in">
            <div class="table-header">
                <div>
                    <h2 class="table-title">Daftar Pengiriman</h2>
                    <p class="table-subtitle">Kelola dan pantau status pengiriman pesanan Anda</p>
                </div>
                <div class="search-container">
                    <i class="iconify search-icon" data-icon="tabler:search"></i>
                    <input type="text" id="searchInput" placeholder="Cari pengiriman..." class="search-input">
                </div>
            </div>
            <div class="table-container">
                <table class="data-table" id="shippingTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>No. Resi</th>
                            <th>Kurir</th>
                            <th>Tanggal Kirim</th>
                            <th>Status Order</th>
                            <th>Status Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippings ?? [] as $shipping)
                        <tr>
                            <td class="font-medium">{{ $shipping->order->order_number }}</td>
                            <td>{{ $shipping->tracking_number ?: 'Belum ada' }}</td>
                            <td>
                                @switch($shipping->shipping_method)
                                    @case('jne')
                                        <span>JNE</span>
                                        @break
                                    @case('jnt')
                                        <span>J&T</span>
                                        @break
                                    @case('pos')
                                        <span>POS Indonesia</span>
                                        @break
                                    @case('sicepat')
                                        <span>SiCepat</span>
                                        @break
                                    @case('anteraja')
                                        <span>AnterAja</span>
                                        @break
                                    @default
                                        <span>{{ $shipping->shipping_method }}</span>
                                @endswitch
                            </td>
                            <td>{{ $shipping->shipped_at ? $shipping->shipped_at->format('d M Y') : 'Belum dikirim' }}</td>
                            <td>
                                @switch($shipping->order->status)
                                    @case('pending')
                                        <span class="badge badge-yellow">Pending</span>
                                        @break
                                    @case('processing')
                                        <span class="badge badge-blue">Processing</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge badge-purple">Shipped</span>
                                        @break
                                    @case('completed')
                                        <span class="badge badge-green">Completed</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-red">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge badge-gray">{{ ucfirst($shipping->order->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($shipping->status)
                                    @case('pending')
                                        <span class="badge badge-yellow">Pending</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge badge-purple">Shipped</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge badge-green">Delivered</span>
                                        @break
                                    @case('failed')
                                        <span class="badge badge-red">Failed</span>
                                        @break
                                    @default
                                        <span class="badge badge-gray">{{ $shipping->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('seller.transactions.shipping.show', $shipping->id) }}" class="action-button action-button-primary">
                                        <i class="iconify mr-1" data-icon="tabler:eye"></i> Detail
                                    </a>
                                    @if($shipping->order->status === 'processing')
                                        <button type="button" class="action-button action-button-success send-shipping" data-id="{{ $shipping->id }}">
                                            <i class="iconify mr-1" data-icon="tabler:send"></i> Kirim
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="iconify text-3xl" data-icon="tabler:package-off"></i>
                                    </div>
                                    <h3 class="empty-title">Tidak ada data pengiriman</h3>
                                    <p class="empty-description">Belum ada pengiriman yang dapat ditampilkan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                @if(isset($shippings) && method_exists($shippings, 'links'))
                    {{ $shippings->withQueryString()->links() }}
                @else
                    <span>Menampilkan {{ count($shippings ?? []) }} pengiriman</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Send Shipping Modal -->
<div id="sendShippingModal" class="modal modal-hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Kirim Pesanan</h3>
        </div>
        <form id="sendShippingForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3" class="form-textarea"></textarea>
                </div>
                <p class="form-hint">
                    Dengan mengklik tombol "Kirim", status pesanan akan berubah menjadi "Shipped" dan tidak dapat diubah kembali.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeModal" class="modal-button-cancel">Batal</button>
                <button type="submit" class="modal-button-submit">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form when filters change
        const statusFilter = document.getElementById('status');
        const methodFilter = document.getElementById('shipping_method');
        const searchInput = document.getElementById('search');
        
        [statusFilter, methodFilter].forEach(element => {
            if (element) {
                element.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filterForm').submit();
            }
        });
        
        // Search functionality
        const tableSearchInput = document.getElementById('searchInput');
        if (tableSearchInput) {
            tableSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#shippingTable tbody tr');
                
                rows.forEach(row => {
                    if (row.querySelector('.empty-state')) {
                        return; // Skip empty state row
                    }
                    
                    const orderId = row.cells[0].textContent.toLowerCase();
                    const trackingNumber = row.cells[1].textContent.toLowerCase();
                    const courier = row.cells[2].textContent.toLowerCase();
                    
                    if (orderId.includes(searchTerm) || trackingNumber.includes(searchTerm) || courier.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Modal functionality
        const modal = document.getElementById('sendShippingModal');
        const closeModal = document.getElementById('closeModal');
        const form = document.getElementById('sendShippingForm');
        const sendButtons = document.querySelectorAll('.send-shipping');
        
        sendButtons.forEach(button => {
            button.addEventListener('click', function() {
                const shippingId = this.getAttribute('data-id');
                form.action = `/seller/transactions/shipping/${shippingId}/send`;
                modal.classList.remove('modal-hidden');
            });
        });
        
        closeModal.addEventListener('click', function() {
            modal.classList.add('modal-hidden');
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('modal-hidden');
            }
        });
        
        // Add hover effects for table rows
        const tableRows = document.querySelectorAll('.data-table tbody tr');
        tableRows.forEach(row => {
            if (!row.querySelector('.empty-state')) {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f9fafb';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            }
        });
        
        // Initialize DataTable if jQuery and DataTables are available
        if (typeof $ !== '' && $.fn.DataTable) {
            $('#shippingTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[3, 'desc']],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
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
                }
            });
        }

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
                        // Submit the form via AJAX
                        fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success');
                                // Optionally, refresh the page or update the UI
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat mengirim pengiriman.', 'error');
                        });
                    }
                });
            });
        }

    });
</script>
@endsection
