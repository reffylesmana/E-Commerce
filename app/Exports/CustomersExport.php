<?php

namespace App\Exports;

use App\Models\User;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CustomersExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct($sellerId, $startDate, $endDate)
    {
        parent::__construct($sellerId, $startDate, $endDate);
        $this->reportTitle = 'LAPORAN PELANGGAN';
    }

    public function query()
    {
        return User::whereHas('orders', function ($query) {
                $query->where('user_id', $this->sellerId)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->where('status', '!=', 'cancelled');
            })
            ->withCount(['orders as order_count' => function ($query) {
                $query->where('user_id', $this->sellerId)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->where('status', '!=', 'cancelled');
            }])
            ->withSum(['orders as total_spent' => function ($query) {
                $query->where('user_id', $this->sellerId)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->withMax(['orders as last_order_date' => function ($query) {
                $query->where('user_id', $this->sellerId)
                    ->where('status', '!=', 'cancelled');
            }], 'created_at')
            ->orderBy('total_spent', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Jumlah Order',
            'Total Belanja',
            'Order Terakhir',
            'Tanggal Registrasi',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->order_count,
            $user->total_spent,
            $user->last_order_date ? date('d/m/Y', strtotime($user->last_order_date)) : 'N/A',
            $user->created_at->format('d/m/Y'),
        ];
    }

    public function title(): string
    {
        return 'Laporan Pelanggan';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 30, // Nama
            'B' => 35, // Email
            'C' => 15, // Jumlah Order
            'D' => 20, // Total Belanja
            'E' => 20, // Order Terakhir
            'F' => 20, // Tanggal Registrasi
        ];
    }
    
    protected function applyCurrencyFormatting($sheet)
    {
        // Format the Total Belanja column as currency
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('D9:D' . $lastRow)->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
    }
}
