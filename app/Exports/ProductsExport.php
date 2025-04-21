<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct($sellerId, $startDate, $endDate)
    {
        parent::__construct($sellerId, $startDate, $endDate);
        $this->reportTitle = 'LAPORAN PRODUK';
    }

    public function query()
    {
        return Product::where('user_id', $this->sellerId)
            ->withCount(['orderItems as sold_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereBetween('created_at', [$this->startDate, $this->endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }])
            ->withSum(['orderItems as revenue' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereBetween('created_at', [$this->startDate, $this->endDate])
                      ->where('status', '!=', 'cancelled');
                });
            }], DB::raw('price * quantity'))
            ->orderBy('sold_count', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'SKU',
            'Harga',
            'Jumlah Terjual',
            'Pendapatan',
            'Stok Tersisa',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->slug,
            $product->price,
            $product->sold_count ?? 0,
            $product->revenue ?? 0,
            $product->stock,
        ];
    }

    public function title(): string
    {
        return 'Laporan Produk';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 40, // Nama Produk
            'B' => 15, // SKU
            'C' => 15, // Harga
            'D' => 15, // Jumlah Terjual
            'E' => 20, // Pendapatan
            'F' => 15, // Stok Tersisa
        ];
    }
    
    protected function applyCurrencyFormatting($sheet)
    {
        // Format the price and revenue columns as currency
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('C9:C' . $lastRow)->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('E9:E' . $lastRow)->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
    }
}
