<?php

namespace App\Filament\Resources;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentReportTable extends Table
{
    protected function getTableQuery()
    {
        $data = request()->all();
        $seller = Auth::user();
        $start = Carbon::parse($data['start_date'] ?? now()->startOfMonth());
        $end = Carbon::parse($data['end_date'] ?? now()->endOfMonth())->endOfDay();

        return Payment::whereHas('transaction.order', function ($query) use ($seller, $start, $end) {
            $query->where('user_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled');
        });
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('payment_method')->label('Payment Method'),
            Tables\Columns\TextColumn::make('transaction_count')->label('Transaction Count'),
            Tables\Columns\TextColumn::make('total_amount')->label('Total Amount')->money('IDR'),
        ];
    }
}