<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class SalesExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function __construct($sellerId, $startDate, $endDate)
    {
        parent::__construct($sellerId, $startDate, $endDate);
        $this->reportTitle = 'LAPORAN PENJUALAN';
    }

    public function query()
    {
        return Order::where('user_id', $this->sellerId)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['user', 'payment'])
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Order Number',
            'Customer',
            'Email',
            'Status',
            'Metode Pembayaran',
            'Total',
        ];
    }

    public function map($order): array
    {
        return [
            $order->created_at->format('d/m/Y H:i'),
            $order->order_number,
            $order->user->name,
            $order->user->email,
            ucfirst($order->status),
            $this->formatPaymentMethod($order->payment->payment_method ?? 'Belum dibayar'),
            $order->total_amount,
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 20, // Tanggal
            'B' => 15, // Invoice
            'C' => 25, // Customer
            'D' => 30, // Email
            'E' => 15, // Status
            'F' => 20, // Metode Pembayaran
            'G' => 20, // Total
        ];
    }
    
    protected function applyCurrencyFormatting($sheet)
    {
        // Format the Total column (G) as currency
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('G9:G' . $lastRow)->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
    }
    
    private function formatPaymentMethod($method)
    {
        $methodNames = [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'e_wallet' => 'E-Wallet',
            'paylater' => 'Paylater',
        ];
        
        return $methodNames[$method] ?? $method;
    }
}
