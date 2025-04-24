<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class QuickStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->count();

        $productCount = Product::where('is_active', true)->count();
        $newCustomers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $topCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->first();

        return [
            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description($monthlyRevenue > 0 ? '↑ ' . number_format($monthlyRevenue/1000000, 1) . ' jt' : 'Belum ada pendapatan')
                ->descriptionIcon($monthlyRevenue > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-minus')
                ->color($monthlyRevenue > 0 ? 'success' : 'gray')
                ->chart($this->getRevenueChartData())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "dispatch('openModal', { component: 'revenue-details' })",
                ]),

            Stat::make('Total Pesanan', $monthlyOrders)
                ->description($monthlyOrders > 0 ? '↑ ' . $monthlyOrders . ' transaksi' : 'Belum ada transaksi')
                ->descriptionIcon($monthlyOrders > 0 ? 'heroicon-o-shopping-bag' : 'heroicon-o-minus')
                ->color($monthlyOrders > 0 ? 'primary' : 'gray'),

            Stat::make('Produk Aktif', $productCount)
                ->description($topCategory ? 'Kategori terlaris: ' . $topCategory->name : 'Belum ada kategori')
                ->descriptionIcon($productCount > 0 ? 'heroicon-o-tag' : 'heroicon-o-minus')
                ->color($productCount > 0 ? 'info' : 'gray'),

            Stat::make('Pelanggan Baru', $newCustomers)
                ->description($newCustomers > 0 ? '↑ ' . $newCustomers . ' pengguna' : 'Belum ada pendaftar')
                ->descriptionIcon($newCustomers > 0 ? 'heroicon-o-user-plus' : 'heroicon-o-minus')
                ->color($newCustomers > 0 ? 'warning' : 'gray')
                ->chart($this->getCustomerGrowthData()),
        ];
    }

    protected function getRevenueChartData(): array
    {
        return DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();
    }

    protected function getCustomerGrowthData(): array
    {
        return DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();
    }
}