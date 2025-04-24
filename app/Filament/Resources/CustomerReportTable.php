<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CustomerReportTable extends Table
{
    protected function getTableQuery()
    {
        $data = request()->all();
        $seller = Auth::user();
        $start = Carbon::parse($data['start_date'] ?? now()->startOfMonth());
        $end = Carbon::parse($data['end_date'] ?? now()->endOfMonth())->endOfDay();

        return User::whereHas('orders', function ($query) use ($seller, $start, $end) {
            $query->where('user_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->where('status', '!=', 'cancelled');
        });
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Customer Name'),
            Tables\Columns\TextColumn::make('email')->label('Email'),
            Tables\Columns\TextColumn::make('orders_count')->label('Total Orders')->counts('orders'),
            Tables\Columns\TextColumn::make('total_spent')->label('Total Spent')->money('IDR'),
        ];
    }
}