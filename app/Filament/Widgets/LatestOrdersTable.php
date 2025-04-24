<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestOrdersTable extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '45s';
    protected int|string|array $columnSpan = 'full';

    protected $sellerId;

    public function mount(): void
    {
        $this->sellerId = Auth::user()->id; // Ambil sellerId dari pengguna yang sedang login
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::whereHas('items.product', function ($q) {
                    $q->where('user_id', $this->sellerId); // Filter berdasarkan sellerId
                })
                ->with(['user', 'items.product']) // Pastikan untuk memuat relasi yang diperlukan
                ->latest()
                ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Order $record) => $record->created_at->diffForHumans()),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Item')
                    ->counts('items')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn (Order $record) => $record->total_amount > 500000 ? 'success' : 'primary'),
                    
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->selectablePlaceholder(false),
                    
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Dibayar')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
                    
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transfer' => 'info',
                        'cod' => 'warning',
                        'credit_card' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->openUrlInNewTab(),
                    
                Tables\Actions\Action::make('print')
                    ->url(fn (Order $record): string => route('order.invoice', $record))
                    ->icon('heroicon-m-printer')
                    ->hidden(fn (Order $record) => $record->status !== 'completed'),
            ])
            ->emptyStateHeading ('Belum ada pesanan')
            ->emptyStateDescription('Pesanan yang dibuat akan muncul di sini')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->deferLoading()
            ->striped()
            ->defaultSort('created_at', 'desc');
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }
}