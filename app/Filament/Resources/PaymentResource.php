<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Pembayaran';

    protected static ?string $pluralLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('transaction_id')
                            ->relationship('transaction', 'invoice_number')
                            ->required()
                            ->label('Transaksi'),
                        Forms\Components\TextInput::make('payment_id')
                            ->required()
                            ->maxLength(255)
                            ->label('ID Pembayaran'),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Transfer Bank',
                                'credit_card' => 'Kartu Kredit',
                                'e_wallet' => 'E-Wallet',
                                'qris' => 'QRIS',
                                'other' => 'Lainnya',
                            ])
                            ->required()
                            ->label('Metode Pembayaran'),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Jumlah Pembayaran'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'success' => 'Berhasil',
                                'failed' => 'Gagal',
                                'expired' => 'Kedaluwarsa',
                                'refunded' => 'Dikembalikan',
                            ])
                            ->required()
                            ->label('Status'),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(65535)
                            ->label('Catatan'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.invoice_number')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Invoice'),
                Tables\Columns\TextColumn::make('payment_id')
                    ->searchable()
                    ->label('ID Pembayaran'),
            Tables\Columns\TextColumn::make('payment_method')
                ->label('Metode Pembayaran')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'bank_transfer' => 'Transfer Bank',
                    'credit_card' => 'Kartu Kredit',
                    'e_wallet' => 'E-Wallet',
                    'qris' => 'QRIS',
                    'other' => 'Lainnya',
                    default => 'Tidak Diketahui',
                }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable()
                    ->label('Jumlah Pembayaran'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => ['failed', 'expired'],
                        'warning' => 'pending',
                        'success' => 'success',
                        'primary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Tanggal Pembayaran'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'success' => 'Berhasil',
                        'failed' => 'Gagal',
                        'expired' => 'Kedaluwarsa',
                        'refunded' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'e_wallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                        'other' => 'Lainnya',
                    ]),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

