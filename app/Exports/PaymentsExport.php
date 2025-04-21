<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaymentsExport extends BaseExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct($sellerId, $startDate, $endDate)
    {
        parent::__construct($sellerId, $startDate, $endDate);
        $this->reportTitle = 'LAPORAN PEMBAYARAN';
    }

    public function collection()
    {
        $totalPaymentAmount = Payment::whereHas('transaction.order', function ($query) {
            $query->where('user_id', $this->sellerId)
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', '!=', 'cancelled');
        })->sum('amount');
        
        $payments = Payment::whereHas('transaction.order', function ($query) {
            $query->where('user_id', $this->sellerId)
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', '!=', 'cancelled');
        })
        ->select('payment_method', 
            DB::raw('COUNT(*) as transaction_count'), 
            DB::raw('SUM(amount) as total_amount'))
        ->groupBy('payment_method')
        ->orderBy('total_amount', 'desc')
        ->get();
        
        // Calculate percentages
        foreach ($payments as $payment) {
            $payment->percentage = $totalPaymentAmount > 0 ? 
                ($payment->total_amount / $totalPaymentAmount) * 100 : 0;
        }
        
        return $payments;
    }

    public function headings(): array
    {
        return [
            'Metode Pembayaran',
            'Jumlah Transaksi',
            'Total Nilai',
            'Persentase',
        ];
    }

    public function map($payment): array
    {
        $methodNames = [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'e_wallet' => 'E-Wallet',
            'paylater' => 'Paylater',
        ];
        
        return [
            $methodNames[$payment->payment_method] ?? $payment->payment_method,
            $payment->transaction_count,
            $payment->total_amount,
            number_format($payment->percentage, 2) . '%',
        ];
    }

    public function title(): string
    {
        return 'Laporan Pembayaran';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 25, // Metode Pembayaran
            'B' => 20, // Jumlah Transaksi
            'C' => 25, // Total Nilai
            'D' => 15, // Persentase
        ];
    }
    
    protected function applyCurrencyFormatting($sheet)
    {
        // Format the Total Nilai column as currency
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('C9:C' . $lastRow)->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
    }
}
