@extends('layouts.seller')

@section('title', 'Keranjang Belanja - Seller Dashboard')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Keranjang Belanja</h2>
    
    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 justify-between">
            <div class="flex flex-col sm:flex-row gap-4">
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Rentang Waktu</label>
                    <select id="date_range" name="date_range" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="today">Hari Ini</option>
                        <option value="yesterday">Kemarin</option>
                        <option value="last_7_days">7 Hari Terakhir</option>
                        <option value="last_30_days">30 Hari Terakhir</option>
                        <option value="this_month">Bulan Ini</option>
                        <option value="last_month">Bulan Lalu</option>
                    </select>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                <div class="relative">
                    <input type="text" id="search" name="search" placeholder="Cari nama produk..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <iconify-icon icon="heroicons:magnifying-glass" class="text-gray-400"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cart Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cartItems ?? [] as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $item->product->thumbnail_url ?? asset('img/placeholder.png') }}" alt="{{ $item->product->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada item di keranjang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            @if(isset($cartItems) && $cartItems->hasPages())
                {{ $cartItems->links() }}
            @endif
        </div>
    </div>
    
    <!-- Cart Analytics -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Total Item di Keranjang</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 mr-4">
                    <iconify-icon icon="heroicons:shopping-cart" class="text-2xl text-indigo-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Item</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalItems ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Produk Terpopuler</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <iconify-icon icon="heroicons:star" class="text-2xl text-green-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Paling Banyak di Keranjang</p>
                    <p class="text-lg font-bold text-gray-800">{{ $popularProduct ?? 'Tidak ada data' }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konversi Keranjang</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <iconify-icon icon="heroicons:arrow-trending-up" class="text-2xl text-blue-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tingkat Konversi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $conversionRate ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Abandoned Cart -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Keranjang Belanja Tertinggal</h3>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Aktif</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($abandonedCarts ?? [] as $cart)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $cart['user']->name }}</div>
                                <div class="text-sm text-gray-500">{{ $cart['user']->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cart['total_quantity'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($cart['total_value'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cart['last_updated']->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('seller.transactions.cart.reminder', $cart['user_id']) }}" class="text-indigo-600 hover:text-indigo-900">Kirim Pengingat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada keranjang tertinggal</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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
    });
</script>
@endsection
