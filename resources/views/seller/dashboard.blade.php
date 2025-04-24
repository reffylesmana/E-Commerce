@extends('layouts.seller')
@section('title', 'Dashboard Seller')

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

        .stat-card.blue::after {
            background-color: #4f46e5;
        }

        .stat-card.green::after {
            background-color: #10b981;
        }

        .stat-card.yellow::after {
            background-color: #f59e0b;
        }

        .stat-card.purple::after {
            background-color: #8b5cf6;
        }

        .stat-card.pink::after {
            background-color: #ec4899;
        }

        .stat-card.red::after {
            background-color: #ef4444;
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

        .stat-icon.purple {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .stat-icon.pink {
            background-color: rgba(236, 72, 153, 0.1);
            color: #ec4899;
        }

        .stat-icon.red {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
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

        .stat-trend {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .stat-trend.up {
            color: #10b981;
        }

        .stat-trend.down {
            color: #ef4444;
        }

        /* Chart cards */
        .chart-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Table cards */
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
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .table-container {
            overflow-x: auto;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .dashboard-table th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .dashboard-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .dashboard-table tr:last-child td {
            border-bottom: none;
        }

        .dashboard-table tr {
            transition: background-color 0.2s ease;
        }

        .dashboard-table tr:hover {
            background-color: #f9fafb;
        }

        /* Badge styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .badge-processing {
            background-color: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .badge-shipped {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .badge-completed {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .badge-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Review stars */
        .stars-container {
            display: flex;
            align-items: center;
        }

        .star {
            color: #f59e0b;
            margin-right: 0.125rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .animate-fade-in-delay-4 {
            animation: fadeIn 0.3s ease-out 0.4s forwards;
            opacity: 0;
        }

        /* Grid layouts */
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-actions {
                margin-top: 1rem;
                width: 100%;
            }
        }
    </style>

    <div class="page-container py-8 px-4">
        <div class="container mx-auto max-w-7xl">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard Seller</h1>
                <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan toko Anda.</p>
                <div class="page-title-underline"></div>
            </div>

            <!-- Date Range Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div class="text-sm text-gray-500 mb-4 md:mb-0">
                    <span class="font-medium">Periode:</span>
                    <select id="dateRangeFilter" class="ml-2 border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="today" @if(request('period') == 'today') selected @endif>Hari Ini</option>
                        <option value="yesterday" @if(request('period') == 'yesterday') selected @endif>Kemarin</option>
                        <option value="last7days" @if(request('period') == 'last7days' || !request('period')) selected @endif>7 Hari Terakhir</option>
                        <option value="last30days" @if(request('period') == 'last30days') selected @endif>30 Hari Terakhir</option>
                        <option value="thisMonth" @if(request('period') == 'thisMonth') selected @endif>Bulan Ini</option>
                        <option value="lastMonth" @if(request('period') == 'lastMonth') selected @endif>Bulan Lalu</option>
                        <option value="custom" @if(request('period') == 'custom') selected @endif>Kustom</option>
                    </select>
                </div>

                <div id="customDateRange" class="hidden flex items-center space-x-2">
                    <input type="date" id="startDate"
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <span>sampai</span>
                    <input type="date" id="endDate"
                        class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <button id="applyDateRange"
                        class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                        Terapkan
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid mb-8">
                <!-- Total Revenue Card -->
                <div class="stat-card green animate-fade-in">
                    <div class="stat-icon green">
                        <i class="iconify text-2xl" data-icon="tabler:currency-dollar"></i>
                    </div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-trend up">
                        <i class="iconify mr-1" data-icon="tabler:arrow-up-right"></i>
                        {{ number_format($revenueGrowth ?? 0, 1) }}% dari periode sebelumnya
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="stat-card blue animate-fade-in-delay-1">
                    <div class="stat-icon blue">
                        <i class="iconify text-2xl" data-icon="tabler:shopping-cart"></i>
                    </div>
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                    <div class="stat-trend {{ ($orderGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                        <i class="iconify mr-1"
                            data-icon="{{ ($orderGrowth ?? 0) >= 0 ? 'tabler:arrow-up-right' : 'tabler:arrow-down-right' }}"></i>
                        {{ number_format(abs($orderGrowth ?? 0), 1) }}% dari periode sebelumnya
                    </div>
                </div>

                <!-- Average Order Value Card -->
                <div class="stat-card purple animate-fade-in-delay-2">
                    <div class="stat-icon purple">
                        <i class="iconify text-2xl" data-icon="tabler:receipt"></i>
                    </div>
                    <div class="stat-label">Rata-rata Pesanan</div>
                    <div class="stat-value">Rp {{ number_format($averageOrderValue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-trend {{ ($aovGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                        <i class="iconify mr-1"
                            data-icon="{{ ($aovGrowth ?? 0) >= 0 ? 'tabler:arrow-up-right' : 'tabler:arrow-down-right' }}"></i>
                        {{ number_format(abs($aovGrowth ?? 0), 1) }}% dari periode sebelumnya
                    </div>
                </div>

                <!-- Conversion Rate Card -->
                <div class="stat-card yellow animate-fade-in-delay-3">
                    <div class="stat-icon yellow">
                        <i class="iconify text-2xl" data-icon="tabler:chart-bar"></i>
                    </div>
                    <div class="stat-label">Tingkat Konversi</div>
                    <div class="stat-value">{{ number_format($conversionRate ?? 0, 1) }}%</div>
                    <div class="stat-trend {{ ($conversionGrowth ?? 0) >= 0 ? 'up' : 'down' }}">
                        <i class="iconify mr-1"
                            data-icon="{{ ($conversionGrowth ?? 0) >= 0 ? 'tabler:arrow-up-right' : 'tabler:arrow-down-right' }}"></i>
                        {{ number_format(abs($conversionGrowth ?? 0), 1) }}% dari periode sebelumnya
                    </div>
                </div>
            </div>

            <!-- Second Row Stats -->
            <div class="stats-grid mb-8">
                <!-- Pending Orders Card -->
                <div class="stat-card yellow animate-fade-in">
                    <div class="stat-icon yellow">
                        <i class="iconify text-2xl" data-icon="tabler:clock"></i>
                    </div>
                    <div class="stat-label">Pesanan Pending</div>
                    <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-xs text-gray-500">Perlu diproses</div>
                        <a href="{{ route('seller.transactions.orders.orders', ['status' => 'pending']) }}"
                            class="text-xs text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                    </div>
                </div>

                <!-- Processing Orders Card -->
                <div class="stat-card blue animate-fade-in-delay-1">
                    <div class="stat-icon blue">
                        <i class="iconify text-2xl" data-icon="tabler:package"></i>
                    </div>
                    <div class="stat-label">Pesanan Diproses</div>
                    <div class="stat-value">{{ $processingOrders ?? 0 }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-xs text-gray-500">Siap dikirim</div>
                        <a href="{{ route('seller.transactions.orders.orders', ['status' => 'processing']) }}"
                            class="text-xs text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                    </div>
                </div>

                <!-- Low Stock Products Card -->
                <div class="stat-card red animate-fade-in-delay-2">
                    <div class="stat-icon red">
                        <i class="iconify text-2xl" data-icon="tabler:alert-triangle"></i>
                    </div>
                    <div class="stat-label">Stok Menipis</div>
                    <div class="stat-value">{{ $lowStockProducts ?? 0 }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-xs text-gray-500">Perlu diisi ulang</div>
                        <a href="{{ route('seller.products.index', ['stock' => 'low']) }}"
                            class="text-xs text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                    </div>
                </div>

                <!-- New Reviews Card -->
                <div class="stat-card pink animate-fade-in-delay-3">
                    <div class="stat-icon pink">
                        <i class="iconify text-2xl" data-icon="tabler:message-star"></i>
                    </div>
                    <div class="stat-label">Ulasan Baru</div>
                    <div class="stat-value">{{ $newReviews ?? 0 }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-xs text-gray-500">Rating rata-rata: {{ number_format($averageRating ?? 0, 1) }}/5
                        </div>
                        <a href="#}" class="text-xs text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                    </div>
                </div>
            </div>

            <!-- Sales Chart -->
            {{-- <div class="chart-card animate-fade-in mb-8">
                <div class="chart-header">
                    <h3 class="chart-title">Penjualan & Pendapatan</h3>
                    <div class="flex space-x-2">
                        <button
                            class="chart-period-btn px-3 py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700"
                            data-period="daily">Harian</button>
                        <button
                            class="chart-period-btn px-3 py-1 text-xs font-medium rounded-md text-gray-500 hover:bg-gray-100"
                            data-period="weekly">Mingguan</button>
                        <button
                            class="chart-period-btn px-3 py-1 text-xs font-medium rounded-md text-gray-500 hover:bg-gray-100"
                            data-period="monthly">Bulanan</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesRevenueChart"></canvas>
                </div>
            </div> --}}

            <!-- Two Column Layout for Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Product Performance Chart -->
                <div class="chart-card animate-fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">Performa Produk</h3>
                        <select id="productPerformanceMetric"
                            class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="sales">Penjualan</option>
                            <option value="revenue">Pendapatan</option>
                            <option value="views">Dilihat</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="productPerformanceChart"></canvas>
                    </div>
                </div>

                <!-- Order Status Chart -->
                <div class="chart-card animate-fade-in-delay-1">
                    <div class="chart-header">
                        <h3 class="chart-title">Status Pesanan</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Three Column Layout for Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Payment Methods Chart -->
                <div class="chart-card animate-fade-in">
                    <div class="chart-header">
                        <h3 class="chart-title">Metode Pembayaran</h3>
                    </div>
                    <div class="chart-container" style="height: 220px;">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>

                <!-- Customer Demographics Chart -->
                <div class="chart-card animate-fade-in-delay-1">
                    <div class="chart-header">
                        <h3 class="chart-title">Demografi Pelanggan</h3>
                    </div>
                    <div class="chart-container" style="height: 220px;">
                        <canvas id="customerDemographicsChart"></canvas>
                    </div>
                </div>

                <!-- Review Ratings Chart -->
                <div class="chart-card animate-fade-in-delay-2">
                    <div class="chart-header">
                        <h3 class="chart-title">Rating Ulasan</h3>
                    </div>
                    <div class="chart-container" style="height: 220px;">
                        <canvas id="reviewRatingsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="table-card animate-fade-in mb-8">
                <div class="table-header">
                    <h3 class="table-title">Pesanan Terbaru</h3>
                    <a href="{{ route('seller.transactions.orders.orders') }}"
                        class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua Pesanan</a>
                </div>
                <div class="table-container">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td class="font-medium">{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge badge-pending">Pending</span>
                                            @break

                                            @case('processing')
                                                <span class="badge badge-processing">Processing</span>
                                            @break

                                            @case('shipped')
                                                <span class="badge badge-shipped">Shipped</span>
                                            @break

                                            @case('completed')
                                                <span class="badge badge-completed">Completed</span>
                                            @break

                                            @case('cancelled')
                                                <span class="badge badge-cancelled">Cancelled</span>
                                            @break

                                            @default
                                                <span class="badge">{{ ucfirst($order->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.transactions.orders.show', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="iconify text-4xl mb-3 text-gray-400"
                                                    data-icon="tabler:shopping-cart-off"></i>
                                                <p class="font-medium text-gray-900 mb-1">Belum ada pesanan</p>
                                                <p class="text-sm">Pesanan baru akan muncul di sini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Two Column Layout for Tables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Top Products Table -->
                    <div class="table-card animate-fade-in">
                        <div class="table-header">
                            <h3 class="table-title">Produk Terlaris</h3>
                            <a href="{{ route('seller.products.index', ['sort' => 'bestselling']) }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                        </div>
                        <div class="table-container">
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Terjual</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts ?? [] as $product)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-10 h-10 flex-shrink-0 rounded-md overflow-hidden bg-gray-100 mr-3">
                                                        @if ($product->photos->isNotEmpty())
                                                            <img src="{{ Storage::url($product->photos->first()->photo) }}"
                                                                alt="{{ $product->name }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div
                                                                class="w-full h-full flex items-center justify-center text-gray-400">
                                                                <i class="iconify text-xl" data-icon="tabler:photo-off"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="truncate">
                                                        <div class="font-medium text-gray-900 truncate">{{ $product->name }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">{{ $product->category->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $product->sold ?? 0 }}</td>
                                            <td>Rp {{ number_format($product->revenue ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-6 text-gray-500">
                                                <p>Belum ada data produk terlaris</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Reviews Table -->
                    <div class="table-card animate-fade-in-delay-1">
                        <div class="table-header">
                            <h3 class="table-title">Ulasan Terbaru</h3>
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                        </div>
                        <div class="table-container">
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Pelanggan</th>
                                        <th>Rating</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentReviews ?? [] as $review)
                                        <tr>
                                            <td class="truncate max-w-[150px]">{{ $review->product->name }}</td>
                                            <td>{{ $review->user->name }}</td>
                                            <td>
                                                <div class="stars-container">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="iconify star"
                                                            data-icon="{{ $i <= $review->rating ? 'tabler:star-filled' : 'tabler:star' }}"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>{{ $review->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-6 text-gray-500">
                                                <p>Belum ada ulasan terbaru</p>
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

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Date Range Filter
                const dateRangeFilter = document.getElementById('dateRangeFilter');
                const customDateRange = document.getElementById('customDateRange');
                const startDateInput = document.getElementById('startDate');
                const endDateInput = document.getElementById('endDate');
                const applyDateRangeBtn = document.getElementById('applyDateRange');

                dateRangeFilter.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateRange.classList.remove('hidden');
                    } else {
                        customDateRange.classList.add('hidden');
                        // Apply the selected date range filter
                        applyDateFilter(this.value);
                    }
                });

                applyDateRangeBtn.addEventListener('click', function() {
                    if (startDateInput.value && endDateInput.value) {
                        applyCustomDateRange(startDateInput.value, endDateInput.value);
                    } else {
                        alert('Silakan pilih tanggal mulai dan tanggal akhir');
                    }
                });

                function applyDateFilter(period) {
                    document.getElementById('dateRangeFilter').value = period;

                    window.location.href = `{{ route('seller.dashboard') }}?period=${period}`;
                }

                function applyCustomDateRange(start, end) {
                    window.location.href = `{{ route('seller.dashboard') }}?period=custom&start=${start}&end=${end}`;
                }

                // Initialize charts with data from backend
                initSalesRevenueChart();
                initProductPerformanceChart();
                initOrderStatusChart();
                initPaymentMethodsChart();
                initCustomerDemographicsChart();
                initReviewRatingsChart();

                // Chart period buttons
                const chartPeriodBtns = document.querySelectorAll('.chart-period-btn');
                chartPeriodBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Remove active class from all buttons
                        chartPeriodBtns.forEach(b => {
                            b.classList.remove('bg-indigo-100', 'text-indigo-700');
                            b.classList.add('text-gray-500', 'hover:bg-gray-100');
                        });

                        // Add active class to clicked button
                        this.classList.remove('text-gray-500', 'hover:bg-gray-100');
                        this.classList.add('bg-indigo-100', 'text-indigo-700');

                        // Update chart data based on selected period
                        updateSalesRevenueChart(this.dataset.period);
                    });
                });

                // Product performance metric selector
                const productPerformanceMetric = document.getElementById('productPerformanceMetric');
                productPerformanceMetric.addEventListener('change', function() {
                    updateProductPerformanceChart(this.value);
                });
            });

            // Sales & Revenue Chart
            function initSalesRevenueChart() {
                const ctx = document.getElementById('salesRevenueChart').getContext('2d');

                // Get data from backend
                const salesRevenueData = @json($salesRevenueData ?? ['labels' => [], 'sales' => [], 'revenue' => []]);

                window.salesRevenueChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: salesRevenueData.labels,
                        datasets: [{
                                label: 'Penjualan',
                                data: salesRevenueData.sales,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                pointRadius: 3,
                                pointBackgroundColor: '#4f46e5',
                                yAxisID: 'y'
                            },
                            {
                                label: 'Pendapatan',
                                data: salesRevenueData.revenue,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                pointRadius: 3,
                                pointBackgroundColor: '#10b981',
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Jumlah Pesanan'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                title: {
                                    display: true,
                                    text: 'Pendapatan (Rp)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.datasetIndex === 1) {
                                            return label + 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        }
                                        return label + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function updateSalesRevenueChart(period) {
                fetch(`{{ route('seller.dashboard.chart-data') }}?period=${period}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.salesRevenueChart.data.labels = data.labels;
                        window.salesRevenueChart.data.datasets[0].data = data.sales;
                        window.salesRevenueChart.data.datasets[1].data = data.revenue;
                        window.salesRevenueChart.update();
                    })
                    .catch(error => {
                        console.error('Error fetching chart data:', error);
                    });
            }

            // Product Performance Chart
            function initProductPerformanceChart() {
                const ctx = document.getElementById('productPerformanceChart').getContext('2d');

                // Get data from backend
                const productData = @json($productPerformanceData ?? ['labels' => [], 'sales' => []]);

                window.productPerformanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: productData.labels,
                        datasets: [{
                            label: 'Penjualan',
                            data: productData.sales,
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(79, 70, 229, 0.6)',
                                'rgba(79, 70, 229, 0.5)',
                                'rgba(79, 70, 229, 0.4)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(79, 70, 229, 1)',
                                'rgba(79, 70, 229, 1)',
                                'rgba(79, 70, 229, 1)',
                                'rgba(79, 70, 229, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Terjual'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            function updateSalesRevenueChart(period) {

                fetch(`{{ route('seller.dashboard.chart-data') }}?period=${period}`)
                    .then(response => {
                        if (!response.ok) throw new Error(response.statusText);
                        return response.json();
                    })
                    .then(data => {
                        // Perbarui chart hanya jika data valid
                        if (data.labels && data.sales && data.revenue) {
                            window.salesRevenueChart.data.labels = data.labels;
                            window.salesRevenueChart.data.datasets[0].data = data.sales;
                            window.salesRevenueChart.data.datasets[1].data = data.revenue;
                            window.salesRevenueChart.update();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Tampilkan pesan error
                        document.getElementById('salesRevenueChart').parentElement.innerHTML =
                            '<div class="text-center text-red-500 py-8">Gagal memuat data chart</div>';
                    });
            }

            // Order Status Chart
            function initOrderStatusChart() {
                const ctx = document.getElementById('orderStatusChart').getContext('2d');

                // Get data from backend
                const orderStatusData = @json($orderStatusData ?? ['labels' => [], 'data' => []]);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: orderStatusData.labels,
                        datasets: [{
                            data: orderStatusData.data,
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderColor: [
                                'rgba(245, 158, 11, 1)',
                                'rgba(79, 70, 229, 1)',
                                'rgba(139, 92, 246, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(239, 68, 68, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.formattedValue;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((context.raw / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Payment Methods Chart
            function initPaymentMethodsChart() {
                const ctx = document.getElementById('paymentMethodsChart').getContext('2d');

                // Get data from backend
                const paymentMethodsData = @json($paymentMethodsData ?? ['labels' => [], 'data' => []]);

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: paymentMethodsData.labels,
                        datasets: [{
                            data: paymentMethodsData.data,
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(139, 92, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.formattedValue;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((context.raw / total) * 100);
                                        return `${label}: ${percentage}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Customer Demographics Chart
            function initCustomerDemographicsChart() {
                const ctx = document.getElementById('customerDemographicsChart').getContext('2d');

                // Get data from backend
                const demographicsData = @json($customerDemographicsData ?? ['labels' => [], 'data' => []]);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: demographicsData.labels, // Home, Office, Other
                        datasets: [{
                            label: 'Alamat Pelanggan',
                            data: demographicsData.data, // Jumlah masing-masing alamat
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Alamat'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            initCustomerDemographicsChart();


            // Review Ratings Chart
            function initReviewRatingsChart() {
                const ctx = document.getElementById('reviewRatingsChart').getContext('2d');

                // Get data from backend
                const ratingsData = @json($reviewRatingsData ?? ['labels' => [], 'data' => []]);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ratingsData.labels,
                        datasets: [{
                            label: 'Jumlah Ulasan',
                            data: ratingsData.data,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(16, 185, 129, 0.8)'
                            ],
                            borderColor: [
                                'rgba(239, 68, 68, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(139, 92, 246, 1)',
                                'rgba(79, 70, 229, 1)',
                                'rgba(16, 185, 129, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Ulasan'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        </script>
    @endsection
