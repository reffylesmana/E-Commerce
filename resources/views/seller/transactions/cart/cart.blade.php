@extends('layouts.seller')

@section('title', 'Keranjang Belanja - Seller Dashboard')

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
    
    .product-image {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        object-fit: cover;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-blue {
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
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
</style>

<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Keranjang Belanja</h1>
            <p class="page-subtitle">Analisis dan kelola keranjang belanja pelanggan Anda</p>
            <div class="page-title-underline"></div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid mb-8">
            <div class="stat-card blue animate-fade-in">
                <div class="stat-icon blue">
                    <i class="iconify text-2xl" data-icon="tabler:shopping-cart"></i>
                </div>
                <div class="stat-label">Total Item di Keranjang</div>
                <div class="stat-value">{{ $totalItems ?? 0 }}</div>
            </div>
            
            <div class="stat-card green animate-fade-in-delay-1">
                <div class="stat-icon green">
                    <i class="iconify text-2xl" data-icon="tabler:star"></i>
                </div>
                <div class="stat-label">Produk Terpopuler</div>
                <div class="stat-value" style="font-size: 1.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $popularProduct->name ?? 'Tidak ada data' }}</div>
            </div>
            
            <div class="stat-card yellow animate-fade-in-delay-2">
                <div class="stat-icon yellow">
                    <i class="iconify text-2xl" data-icon="tabler:chart-bar"></i>
                </div>
                <div class="stat-label">Tingkat Konversi</div>
                <div class="stat-value">{{ number_format($conversionRate ?? 0, 2) }}%</div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card animate-fade-in">
            <h2 class="filter-title">Filter Keranjang</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date_range" class="filter-label">Rentang Waktu</label>
                    <select id="date_range" name="date_range" class="filter-select">
                        <option value="today" {{ $dateRange == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="yesterday" {{ $dateRange == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                        <option value="last_7_days" {{ $dateRange == 'last_7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="last_30_days" {{ $dateRange == 'last_30_days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="this_month" {{ $dateRange == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" {{ $dateRange == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="search" class="filter-label">Cari Produk</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Cari nama produk..." class="filter-input pl-10" value="{{ request('search') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="iconify text-gray-400" data-icon="tabler:search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart Items Table -->
        <div class="table-card animate-fade-in">
            <div class="table-header">
                <div>
                    <h2 class="table-title">Daftar Item Keranjang</h2>
                    <p class="table-subtitle">Item yang ditambahkan pelanggan ke keranjang belanja</p>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Customer</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cartItems ?? [] as $item)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="product-image" src="{{ asset('storage/' . $item->product->photos->first()->photo) }}" alt="{{ $item->product->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                            </td>
                            <td class="text-sm font-medium">{{ $item->quantity }}</td>
                            <td class="text-sm">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="text-sm font-medium">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                            <td class="text-sm text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="iconify text-3xl" data-icon="tabler:shopping-cart-off"></i>
                                    </div>
                                    <h3 class="empty-title">Tidak ada item di keranjang</h3>
                                    <p class="empty-description">Belum ada pelanggan yang menambahkan produk ke keranjang belanja.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="table-footer">
                @if(isset($cartItems) && $cartItems->hasPages())
                    {{ $cartItems->appends(request()->except('page'))->links() }}
                @else
                    <span>Menampilkan {{ count($cartItems ?? []) }} item</span>
                @endif
            </div>
        </div>
        
        <!-- Abandoned Cart -->
        <div class="animate-fade-in">
            <div class="flex items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Keranjang Belanja Tertinggal</h2>
                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">{{ count($abandonedCarts ?? []) }}</span>
            </div>
            
            <div class="table-card">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Jumlah Item</th>
                                <th>Total Nilai</th>
                                <th>Terakhir Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($abandonedCarts ?? [] as $cart)
                            <tr>
                                <td>
                                    <div class="text-sm font-medium text-gray-900">{{ $cart['user']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $cart['user']->email }}</div>
                                </td>
                                <td class="text-sm font-medium">{{ $cart['total_quantity'] }}</td>
                                <td class="text-sm font-medium">Rp {{ number_format($cart['total_value'], 0, ',', '.') }}</td>
                                <td class="text-sm text-gray-500">{{ $cart['last_updated']->diffForHumans() }}</td>
                                <td>
                                    <form action="{{ route('seller.transactions.cart.reminder', $cart['user_id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="action-button action-button-primary">
                                            <i class="iconify mr-1" data-icon="tabler:mail"></i> Kirim Pengingat
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="iconify text-3xl" data-icon="tabler:check-circle"></i>
                                        </div>
                                        <h3 class="empty-title">Tidak ada keranjang tertinggal</h3>
                                        <p class="empty-description">Semua pelanggan telah menyelesaikan pembelian mereka.</p>
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
</div>

<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const dateRangeFilter = document.getElementById('date_range');
        const searchInput = document.getElementById('search');
        
        dateRangeFilter.addEventListener('change', applyFilters);
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        function applyFilters() {
            const dateRange = dateRangeFilter.value;
            const search = searchInput.value;
            
            let url = new URL(window.location.href);
            
            if (dateRange) url.searchParams.set('date_range', dateRange);
            else url.searchParams.delete('date_range');
            
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            
            window.location.href = url.toString();
        }
        
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
    });
</script>
@endsection
