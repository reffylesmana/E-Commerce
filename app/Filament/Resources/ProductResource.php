<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Mail\StoreViolationNotification;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Added proper Log facade import
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Carbon\Carbon;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $label = 'Produk';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photos.photo')  
                    ->label('Gambar Produk')
                    ->stacked()
                    ->limit(3)
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(url('/images/placeholder-product.png')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')->label('Stok')->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.violation_count')
                    ->label('Pelanggaran')
                    ->badge()
                    ->color(fn ($state): string => match(true) {
                        $state === 0 || $state === null => 'success',
                        $state === 1 => 'warning',
                        $state >= 2 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => $state ?? '0'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // Filter untuk menampilkan data yang dihapus
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Untuk melihat detail produk
                Tables\Actions\DeleteAction::make(), // Untuk menghapus produk
                Tables\Actions\Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Product $record): bool => $record->is_active)
                    ->action(function (Product $record): void {
                        $record->is_active = false;
                        $record->save();
                        
                        Notification::make()
                            ->title('Produk dinonaktifkan')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('report_violation')
                    ->label('Laporkan Pelanggaran')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Pelanggaran')
                            ->required(),
                    ])
                    ->action(function (Product $record, array $data): void {
                        // Get the store
                        $store = $record->store;
                        
                        // Initialize violation count if null
                        if ($store->violation_count === null) {
                            $store->violation_count = 0;
                        }
                        
                        // Increment violation count
                        $store->increment('violation_count');
                        $store->last_violation_reason = $data['reason'];
                        $store->last_violation_at = now();
                        
                        // Determine action based on violation count
                        $action = match($store->violation_count) {
                            1 => 'warning',
                            2 => 'ban_7_days',
                            3 => 'ban_14_days',
                            default => 'permanent_ban',
                        };
                        
                        // Apply the action
                        switch ($action) {
                            case 'warning':
                                // Just a warning, no ban
                                break;
                            case 'ban_7_days':
                                $store->is_banned = true;
                                $store->banned_until = Carbon::now()->addDays(7);
                                break;
                            case 'ban_14_days':
                                $store->is_banned = true;
                                $store->banned_until = Carbon::now()->addDays(14);
                                break;
                            case 'permanent_ban':
                                $store->is_banned = true;
                                $store->banned_until = null; // Null means permanent
                                break;
                        }
                        
                        $store->save();
                        
                        // Deactivate the product
                        $record->is_active = false;
                        $record->save();
                        
                        // Send email notification
                        try {
                            Mail::to($store->user->email)
                                ->send(new StoreViolationNotification(
                                    $store,
                                    $record,
                                    $data['reason'],
                                    $action
                                ));
                        } catch (\Exception $e) {
                            // Log email error but continue
                            Log::error('Failed to send violation email: ' . $e->getMessage());
                        }
                        
                        // Show notification
                        Notification::make()
                            ->title('Pelanggaran dilaporkan')
                            ->success()
                            ->body("Toko telah diberitahu dan tindakan yang sesuai telah diambil.")
                            ->send();
                    }),
            ])
            ->bulkActions([]); // Kosongkan bulk actions
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Section::make('Gambar Produk')
                        ->schema([
                            Forms\Components\FileUpload::make('images')
                                ->multiple()
                                ->image()
                                ->disk('public')
                                ->directory('product-photos')
                                ->disabled()
                                ->columnSpanFull()
                                ->imagePreviewHeight('250')
                                ->panelLayout('grid')
                                ->visibility('public'),
                        ]),
                        Forms\Components\TextInput::make('id')
                            ->label('ID')
                            ->disabled(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Produk')
                            ->disabled(),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->disabled()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('stock')
                            ->label('Stok')
                            ->disabled(),
                        Forms\Components\TextInput::make('weight')
                            ->label('Berat')
                            ->disabled()
                            ->suffix('gram'),
                        Forms\Components\TextInput::make('size')
                            ->label('Ukuran')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->disabled(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Deskripsi')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Informasi Toko')
                    ->schema([
                        Forms\Components\TextInput::make('store.name')
                            ->label('Nama Toko')
                            ->disabled(),
                        Forms\Components\TextInput::make('store.violation_count')
                            ->label('Jumlah Pelanggaran')
                            ->disabled(),
                        Forms\Components\Toggle::make('store.is_banned')
                            ->label('Status Banned')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('store.banned_until')
                            ->label('Banned Sampai')
                            ->disabled(),
                        Forms\Components\Textarea::make('store.last_violation_reason')
                            ->label('Alasan Pelanggaran Terakhir')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informasi Sistem')
                    ->schema([
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Dibuat Pada')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }
}