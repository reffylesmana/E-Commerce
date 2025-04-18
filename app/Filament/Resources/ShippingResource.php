<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Models\Shipping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Pengiriman';

    protected static ?string $pluralLabel = 'Pengiriman';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->required()
                            ->label('Pesanan'),
                        Forms\Components\TextInput::make('tracking_number')
                            ->required()
                            ->maxLength(255)
                            ->label('Nomor Resi'),
                        Forms\Components\Select::make('shipping_method')
                            ->options([
                                'jne' => 'JNE',
                                'jnt' => 'J&T',
                                'pos' => 'POS Indonesia',
                                'sicepat' => 'SiCepat',
                                'anteraja' => 'AnterAja',
                                'other' => 'Lainnya',
                            ])
                            ->required()
                            ->label('Kurir'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Pengiriman',
                                'shipped' => 'Dikirim',
                                'in_transit' => 'Dalam Perjalanan',
                                'delivered' => 'Diterima',
                                'failed' => 'Gagal',
                                'returned' => 'Dikembalikan',
                            ])
                            ->required()
                            ->label('Status'),
                        Forms\Components\DateTimePicker::make('shipped_at')
                            ->label('Tanggal Pengiriman'),
                        Forms\Components\DateTimePicker::make('delivered_at')
                            ->label('Tanggal Diterima'),
                        Forms\Components\Textarea::make('shipping_address')
                            ->required()
                            ->label('Alamat Pengiriman'),
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
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Pesanan'),
                Tables\Columns\TextColumn::make('tracking_number')
                    ->searchable()
                    ->label('Nomor Resi'),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->label('Kurir')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'jne' => 'JNE',
                        'jnt' => 'J&T',
                        'pos' => 'POS Indonesia',
                        'sicepat' => 'SiCepat',
                        'anteraja' => 'AnterAja',
                        'other' => 'Lainnya',
                        default => 'Tidak Diketahui',
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => ['failed', 'returned'],
                        'warning' => 'pending',
                        'success' => 'delivered',
                        'primary' => ['shipped', 'in_transit'],
                    ]),
                Tables\Columns\TextColumn::make('shipped_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Tanggal Pengiriman'),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Tanggal Diterima'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Pengiriman',
                        'shipped' => 'Dikirim',
                        'in_transit' => 'Dalam Perjalanan',
                        'delivered' => 'Diterima',
                        'failed' => 'Gagal',
                        'returned' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('shipping_method')
                    ->options([
                        'jne' => 'JNE',
                        'jnt' => 'J&T',
                        'pos' => 'POS Indonesia',
                        'sicepat' => 'SiCepat',
                        'anteraja' => 'AnterAja',
                        'other' => 'Lainnya',
                    ]),
                Tables\Filters\Filter::make('shipped_at')
                    ->form([
                        Forms\Components\DatePicker::make('shipped_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('shipped_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['shipped_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('shipped_at', '>=', $date),
                            )
                            ->when(
                                $data['shipped_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('shipped_at', '<=', $date),
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
            'index' => Pages\ListShippings::route('/'),
            'create' => Pages\CreateShipping::route('/create'),
            'edit' => Pages\EditShipping::route('/{record}/edit'),
        ];
    }
}

