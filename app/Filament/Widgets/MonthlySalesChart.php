<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MonthlySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Bulanan';
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '300px';
    public ?string $filter = 'year';

    public ?string $filterYear;

    protected function getData(): array
    {
        $selectedYear = $this->filterYear ?? now()->year;
        
        // Data tahun berjalan
        $currentData = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('created_at', $selectedYear)
            ->where('status', 'completed')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Data tahun sebelumnya
        $previousData = DB::table('orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('created_at', $selectedYear - 1)
            ->where('status', 'completed')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Format data untuk chart
        $currentYearValues = [];
        $previousYearValues = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $currentYearValues[] = ($currentData[$month] ?? 0) / 1000000; // Konversi ke juta
            $previousYearValues[] = ($previousData[$month] ?? 0) / 1000000;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tahun ' . $selectedYear,
                    'data' => $currentYearValues,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Tahun ' . ($selectedYear - 1),
                    'data' => $previousYearValues,
                    'backgroundColor' => 'rgba(209, 213, 219, 0.6)',
                    'borderColor' => 'rgba(209, 213, 219, 1)',
                    'borderWidth' => 1,
                    'borderDash' => [5, 5],
                    'tension' => 0.3,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        $years = range(now()->year, now()->year - 5);
        return array_combine($years, $years);
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah (dalam juta Rupiah)',
                    ],
                    'min' => 0,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "jt"; }',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { 
                            return context.dataset.label + ": Rp " + context.raw.toLocaleString("id-ID") + "jt"; 
                        }',
                    ],
                ],
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'maintainAspectRatio' => false,
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}