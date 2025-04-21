@extends('layouts.seller')
@section('title', 'Kelola Produk')

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
    
    /* Products table */
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
    
    .product-image {
        width: 4rem;
        height: 4rem;
        border-radius: 0.5rem;
        object-fit: cover;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .product-image-single {
        width: 5rem;
        height: 5rem;
    }
    
    .product-placeholder {
        width: 4rem;
        height: 4rem;
        border-radius: 0.5rem;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }
    
    .product-name {
        font-weight: 600;
        color: #111827;
        font-size: 0.95rem;
        max-width: 15rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
    
    .stock-normal {
        color: #111827;
    }
    
    .stock-low {
        color: #f59e0b;
        font-weight: 500;
    }
    
    .stock-out {
        color: #ef4444;
        font-weight: 500;
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
    
    .action-button:focus {
        outline: none;
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
    
    /* Add product button */
    .add-button {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
    }
    
    .add-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2), 0 4px 6px -2px rgba(79, 70, 229, 0.1);
    }
    
    .add-button:active {
        transform: translateY(0);
    }
    
    .add-button-icon {
        margin-right: 0.5rem;
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
    
    /* Image tooltip */
    .image-tooltip {
        position: relative;
        display: inline-block;
    }
    
    .image-tooltip:hover .tooltip-image {
        display: block;
    }
    
    .tooltip-image {
        display: none;
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translate(-50%, -100%);
        padding: 5px;
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 10;
    }
    
    .tooltip-image img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 0.375rem;
    }
</style>
<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="page-header">
                <h1 class="page-title">Kelola Produk</h1>
                <p class="page-subtitle">Kelola semua produk toko Anda di satu tempat</p>
                <div class="page-title-underline"></div>
            </div>
            
            <a href="{{ route('seller.products.create') }}" class="add-button mt-4 md:mt-0 animate-fade-in">
                <i class="iconify add-button-icon" data-icon="tabler:plus"></i>
                Tambah Produk
            </a>
        </div>
        <!-- Stats Cards -->
        <div class="stats-grid mb-8">
            <!-- Total Products Card -->
            <div class="stat-card blue animate-fade-in">
                <div class="stat-icon blue">
                    <i class="iconify text-2xl" data-icon="tabler:box"></i>
                </div>
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            
            <!-- Active Products Card -->
            <div class="stat-card green animate-fade-in-delay-1">
                <div class="stat-icon green">
                    <i class="iconify text-2xl" data-icon="tabler:check-circle"></i>
                </div>
                <div class="stat-label">Produk Aktif</div>
                <div class="stat-value">{{ $stats['active'] }}</div>
            </div>
            
            <!-- Low Stock Products Card -->
            <div class="stat-card yellow animate-fade-in-delay-2">
                <div class="stat-icon yellow">
                    <i class="iconify text-2xl" data-icon="tabler:alert-triangle"></i>
                </div>
                <div class="stat-label">Stok Menipis</div>
                <div class="stat-value">{{ $stats['low_stock'] }}</div>
            </div>
        </div>
        
        <!-- Products Table -->
        <div class="table-card animate-fade-in">
            <div class="table-header">
                <div>
                    <h2 class="table-title">Daftar Produk</h2>
                    <p class="table-subtitle">Kelola semua produk toko Anda</p>
                </div>
                
                <div class="search-container">
                    <i class="iconify search-icon" data-icon="tabler:search"></i>
                    <input type="text" id="searchInput" placeholder="Cari produk..." class="search-input">
                </div>
            </div>
            
            <div class="table-container">
                <table class="products-table" id="productsTable">
                    <thead>
                        <tr>
                            <th width="15%">Foto</th>
                            <th width="20%">Nama Produk</th>
                            <th width="15%">Kategori</th>
                            <th width="12%">Harga</th>
                            <th width="8%">Stok</th>
                            <th width="10%">Status</th>
                            <th width="8%">Terjual</th>
                            <th width="12%" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->photos->isNotEmpty())
                                    @if($product->photos->count() == 1)
                                        <img class="product-image product-image-single" src="{{ Storage::url($product->photos->first()->photo) }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="image-tooltip">
                                            <img class="product-image" src="{{ Storage::url($product->photos->first()->photo) }}" alt="{{ $product->name }}">
                                            @if($product->photos->count() > 1)
                                                <div class="tooltip-image">
                                                    <img src="{{ Storage::url($product->photos->first()->photo) }}" alt="{{ $product->name }}">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <div class="product-placeholder">
                                        <i class="iconify text-xl" data-icon="tabler:photo"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="product-name">{{ $product->name }}</div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="{{ $product->stock <= 0 ? 'stock-out' : ($product->stock <= 5 ? 'stock-low' : 'stock-normal') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $product->sold ?? 0 }}</td>
                            <td class="text-right">
                                <div class="flex justify-end space-x-1">
                                    <a href="{{ route('seller.products.edit', $product->id) }}" class="action-button action-button-edit">
                                        <i class="iconify mr-1" data-icon="tabler:edit"></i> Edit
                                    </a>
                                    <button type="button" onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" class="action-button action-button-delete">
                                        <i class="iconify mr-1" data-icon="tabler:trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="iconify text-3xl" data-icon="tabler:box-off"></i>
                                    </div>
                                    <h3 class="empty-title">Belum ada produk</h3>
                                    <p class="empty-description">Anda belum memiliki produk. Mulai tambahkan produk untuk ditampilkan di toko Anda.</p>
                                    <a href="{{ route('seller.products.create') }}" class="add-button">
                                        <i class="iconify add-button-icon" data-icon="tabler:plus"></i>
                                        Tambah Produk Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                Menampilkan <span class="font-medium">{{ $products->count() }}</span> produk
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                filterProducts(this.value);
            }, 300));
        }
        
        // Hover effects for table rows
        const tableRows = document.querySelectorAll('#productsTable tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f9fafb';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Delete confirmation
        window.confirmDelete = function(productId, productName) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: `Apakah Anda yakin ingin menghapus produk "${productName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                backdrop: `rgba(0,0,0,0.4)`,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteProduct(productId);
                }
            });
        };
    });
    
    function filterProducts(searchTerm) {
        searchTerm = searchTerm.toLowerCase();
        const rows = document.querySelectorAll('#productsTable tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) {
                return; // Skip empty state row
            }
            
            const productName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            
            if (productName.includes(searchTerm) || category.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show empty message if no results
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && !row.querySelector('.empty-state'));
        const tableBody = document.querySelector('#productsTable tbody');
        const existingEmptyRow = document.querySelector('#emptySearchRow');
        
        if (visibleRows.length === 0 && searchTerm && !existingEmptyRow) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'emptySearchRow';
            emptyRow.innerHTML = `
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="iconify text-3xl" data-icon="tabler:search-off"></i>
                        </div>
                        <h3 class="empty-title">Tidak ada hasil</h3>
                        <p class="empty-description">Tidak ada produk yang cocok dengan pencarian "${searchTerm}"</p>
                    </div>
                </td>
            `;
            tableBody.appendChild(emptyRow);
        } else if ((visibleRows.length > 0 || !searchTerm) && existingEmptyRow) {
            existingEmptyRow.remove();
        }
    }
    
    function deleteProduct(productId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/seller/products/${productId}`;
    form.style.display = 'none';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}
    
    // Utility functions
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
</script>
@if(session('success') && session('show_options'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Tambah Produk Lain',
                cancelButtonText: 'Kembali ke Daftar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('seller.products.create') }}';
                }
            });
        });
    </script>
@endif
@endsection

