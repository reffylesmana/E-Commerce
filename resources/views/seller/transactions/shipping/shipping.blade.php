@extends('layouts.seller')

@section('title', 'Pengiriman - Seller Dashboard')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Pengiriman</h2>
    
    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 justify-between">
            <div class="flex flex-col sm:flex-row gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="shipped">Shipped</option>
                        <option value="in_transit">In Transit</option>
                        <option value="delivered">Delivered</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-1">Kurir</label>
                    <select id="shipping_method" name="shipping_method" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Semua Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="jnt">J&T</option>
                        <option value="pos">POS Indonesia</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="anteraja">AnterAja</option>
                    </select>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pengiriman</label>
                <div class="relative">
                    <input type="text" id="search" name="search" placeholder="Cari nomor resi..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <iconify-icon icon="heroicons:magnifying-glass" class="text-gray-400"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Shipping Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Resi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kirim</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shippings ?? [] as $shipping)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shipping->order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $shipping->tracking_number ?: 'Belum ada' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $shipping->shipped_at ? $shipping->shipped_at->format('d M Y') : 'Belum dikirim' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($shipping->status)
                                @case('pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Shipped</span>
                                    @break
                                @case('in_transit')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">In Transit</span>
                                    @break
                                @case('delivered')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Delivered</span>
                                    @break
                                @case('failed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $shipping->status }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('seller.transactions.shipping.show', $shipping->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                            <button type="button" class="text-gray-600 hover:text-gray-900 update-shipping" data-id="{{ $shipping->id }}">Kirim</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data pengiriman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            @if(isset($shippings) && $shippings->hasPages())
                {{ $shippings->links() }}
            @endif
        </div>
    </div>
    
    <!-- Shipping Summary -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pending</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <iconify-icon icon="heroicons:clock" class="text-2xl text-yellow-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Menunggu Pengiriman</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Shipped</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <iconify-icon icon="heroicons:truck" class="text-2xl text-blue-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sedang Dikirim</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $shippedCount ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Delivered</h3>
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <iconify-icon icon="heroicons:check-circle" class="text-2xl text-green-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Terkirim</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $deliveredCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Shipping Modal -->
<div id="updateShippingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Update Status Pengiriman</h3>
        </div>
        <form id="updateShippingForm" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi</label>
                    <input type="text" id="tracking_number" name="tracking_number" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div class="mb-4">
                    <label for="shipping_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="shipping_status" name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="pending">Pending</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-lg">
                <button type="button" id="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">Batal</button>
                <button type="submit" class="ml-3 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('.update-shipping');
        const modal = document.getElementById('updateShippingModal');
        const closeModal = document.getElementById('closeModal');
        const form = document.getElementById('updateShippingForm');
        
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const shippingId = this.getAttribute('data-id');
                form.action = `/seller/transactions/shipping/${shippingId}/update`;
                modal.classList.remove('hidden');
            });
        });
        
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
        
        // Filter functionality
        const statusFilter = document.getElementById('status');
        const methodFilter = document.getElementById('shipping_method');
        const searchInput = document.getElementById('search');
        
        [statusFilter, methodFilter].forEach(element => {
            element.addEventListener('change', applyFilters);
        });
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        function applyFilters() {
            const status = statusFilter.value;
            const method = methodFilter.value;
            const search = searchInput.value;
            
            let url = new URL(window.location.href);
            
            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');
            
            if (method) url.searchParams.set('shipping_method', method);
            else url.searchParams.delete('shipping_method');
            
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            
            window.location.href = url.toString();
        }
    });
</script>
@endsection
