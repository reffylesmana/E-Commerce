<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ReportResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard'; 

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 7;

    protected static ?string $label = 'Laporan Transaksi';

    protected static ?string $pluralLabel = 'Laporan Transaksi';

    protected static ?string $slug = 'reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Not needed for reports
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('total_transactions')
                    ->label('Jumlah Transaksi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Penjualan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_amount')
                    ->label('Rata-rata Transaksi')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_details')
                    ->label('Lihat Detail')
                    ->url(fn ($record) => route('filament.resources.reports.view', ['record' => $record->date])),
            ])
            ->bulkActions([])
            ->poll('60s');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('AVG(total_amount) as average_amount'),
            ])
            ->groupBy('date')
            ->orderBy('date', 'desc');
    }
}

