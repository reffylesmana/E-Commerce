@extends('layouts.seller')

@section('title', 'Laporan Transaksi - Seller Dashboard')

@section('content')
<div class="container px-6 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Laporan Transaksi</h2>

        <!-- Sales Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Total Penjualan</h3>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <iconify-icon icon="heroicons:banknotes" class="text-2xl text-green-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Periode Ini</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Jumlah Pesanan</h3>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 mr-4">
                        <iconify-icon icon="heroicons:shopping-bag" class="text-2xl text-indigo-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Order</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalOrders ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Rata-rata Order</h3>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <iconify-icon icon="heroicons:calculator" class="text-2xl text-blue-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nilai Rata-rata</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($averageOrderValue ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pelanggan Baru</h3>
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <iconify-icon icon="heroicons:user-plus" class="text-2xl text-yellow-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Periode Ini</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $newCustomers ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 justify-between">
            <div class="flex flex-col sm:flex-row gap-4">
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                    <select id="report_type" name="report_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="sales" {{ $reportType == 'sales' ? 'selected' : '' }}>Penjualan</option>
                        <option value="products" {{ $reportType == 'products' ? 'selected' : '' }}>Produk</option>
                        <option value="customers" {{ $reportType == 'customers' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="payments" {{ $reportType == 'payments' ? 'selected' : '' }}>Pembayaran</option>
                    </select>
                </div>
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="date_start" name="date_start" value="{{ $startDate->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" id="date_end" name="date_end" value="{{ $endDate->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>
            </div>
            <div class="flex items-end">
                <button type="button" id="generate_report" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Generate Laporan
                </button>
                <button type="button" id="export_report" class="ml-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <iconify-icon icon="heroicons:document-arrow-down" class="inline-block mr-1"></iconify-icon> Export
                </button>
            </div>
        </div>
    </div>
    
    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan</h3>
        <div class="w-full h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    
    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800" id="report_title">
                @switch($reportType)
                    @case('sales')
                        Laporan Penjualan
                        @break
                    @case('products')
                        Laporan Produk
                        @break
                    @case('customers')
                        Laporan Pelanggan
                        @break
                    @case('payments')
                        Laporan Pembayaran
                        @break
                    @default
                        Laporan Penjualan
                @endswitch
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="sales_report" style="{{ $reportType == 'sales' ? '' : 'display: none;' }}">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions ?? [] as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->order_number ?? $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($transaction->status)
                                @case('pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @break
                                @case('paid')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                    @break
                                @case('processing')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Processing</span>
                                    @break
                                @case('shipped')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Shipped</span>
                                    @break
                                @case('completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $transaction->status }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->payment->payment_method ?? 'Belum dibayar' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <table class="min-w-full divide-y divide-gray-200" id="products_report" style="{{ $reportType == 'products' ? '' : 'display: none;' }}">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productReports ?? [] as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                                <img src="{{ asset('storage/' . $product->photos->first()->photo) }}"
                                                    alt="Foto Produk" class="object-cover w-full h-full">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->sold_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->revenue ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data produk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <table class="min-w-full divide-y divide-gray-200" id="customers_report" style="{{ $reportType == 'customers' ? '' : 'display: none;' }}">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Order</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Terakhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customerReports ?? [] as $customer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->order_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Carbon\Carbon::parse($customer->last_order_date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <table class="min-w-full divide-y divide-gray-200" id="payments_report" style="{{ $reportType == 'payments' ? '' : 'display: none;' }}">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($paymentReports ?? [] as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @switch($payment->payment_method)
                                @case('bank_transfer')
                                    <span>Bank Transfer</span>
                                    @break
                                @case('credit_card')
                                    <span>Credit Card</span>
                                    @break
                                @case('e_wallet')
                                    <span>E-Wallet</span>
                                    @break
                                @case('paylater')
                                    <span>Paylater</span>
                                    @break
                                @default
                                    <span>{{ $payment->payment_method }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->transaction_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($payment->percentage, 2) }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data pembayaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            @if(isset($transactions) && method_exists($transactions, 'links') && $reportType == 'sales')
                {{ $transactions->appends(request()->except('page'))->links() }}
            @endif
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs with current month if not set
        if (!document.getElementById('date_start').value) {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            document.getElementById('date_start').valueAsDate = firstDay;
            document.getElementById('date_end').valueAsDate = lastDay;
        }
        
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($chartData ?? []) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });
        
        // Report Type Switcher
        const reportType = document.getElementById('report_type');
        const reportTitle = document.getElementById('report_title');
        const salesReport = document.getElementById('sales_report');
        const productsReport = document.getElementById('products_report');
        const customersReport = document.getElementById('customers_report');
        const paymentsReport = document.getElementById('payments_report');
        
        reportType.addEventListener('change', function() {
            // Hide all reports
            salesReport.style.display = 'none';
            productsReport.style.display = 'none';
            customersReport.style.display = 'none';
            paymentsReport.style.display = 'none';
            
            // Show selected report
            switch(this.value) {
                case 'sales':
                    reportTitle.textContent = 'Laporan Penjualan';
                    salesReport.style.display = '';
                    break;
                case 'products':
                    reportTitle.textContent = 'Laporan Produk';
                    productsReport.style.display = '';
                    break;
                case 'customers':
                    reportTitle.textContent = 'Laporan Pelanggan';
                    customersReport.style.display = '';
                    break;
                case 'payments':
                    reportTitle.textContent = 'Laporan Pembayaran';
                    paymentsReport.style.display = '';
                    break;
            }
        });
        
        // Generate Report Button
        const generateReportBtn = document.getElementById('generate_report');
        generateReportBtn.addEventListener('click', function() {
            const reportType = document.getElementById('report_type').value;
            const dateStart = document.getElementById('date_start').value;
            const dateEnd = document.getElementById('date_end').value;
            
            if (!dateStart || !dateEnd) {
                alert('Silakan pilih rentang tanggal');
                return;
            }
            
            window.location.href = `{{ route('seller.reports') }}?type=${reportType}&start=${dateStart}&end=${dateEnd}`;
        });
        
        // Export Report Button
        const exportReportBtn = document.getElementById('export_report');
        exportReportBtn.addEventListener('click', function() {
            const reportType = document.getElementById('report_type').value;
            const dateStart = document.getElementById('date_start').value;
            const dateEnd = document.getElementById('date_end').value;
            
            if (!dateStart || !dateEnd) {
                alert('Silakan pilih rentang tanggal');
                return;
            }
            
            window.location.href = `{{ route('seller.reports.export') }}?type=${reportType}&start=${dateStart}&end=${dateEnd}`;
        });
    });
</script>
@endsection
