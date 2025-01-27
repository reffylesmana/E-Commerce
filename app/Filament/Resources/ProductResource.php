<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Filament\Notifications\Collection;
use Filament\Tables\Columns\ImageColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $label = 'Produk';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                ->label('Pilih Kategori')
                ->options(Category::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),
                Hidden::make('user_id')
                ->default(Auth::user()->id),
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->placeholder('Isi Nama Produk')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),
                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->placeholder('Slug akan diisi otomatis setelah mengisi nama produk')
                    ->maxLength(255)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),
                TextInput::make('price')
                    ->label('Harga Produk (Rupiah)')
                    ->placeholder('Isi Harga Produk (Rupiah)')
                    ->required()
                    ->integer()
                    ->maxLength(255)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),
                TextInput::make('size')
                    ->label('Ukuran Produk')
                    ->placeholder('Isi Ukuran Produk')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),
                TextInput::make('weight')
                    ->label('Bobot Berat Per Kilogram')
                    ->placeholder('Isi Bobot Berat Per Kilogram')
                    ->required()
                    ->integer()
                    ->maxLength(255)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),
                TextInput::make('stock')
                    ->label('Stok Produk')
                    ->placeholder('Isi Stok Produk')
                    ->required()
                    ->integer()
                    ->maxLength(255)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 1
                    ]),

                Repeater::make('photos')
                ->relationship('photos')
                ->schema([
                    FileUpload::make('photo')
                    ->required()
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->directory('product-photos')
                    ->columnSpanFull()
                ])
                ->label('Foto Produk')
                ->grid([
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 4,
                ])
                ->columnSpanFull()
                ->required(),

                Textarea::make('description')
                    ->label('Deskripsi Produk')
                    ->placeholder('Isi Deskripsi Produk')
                    ->required()
                    ->rows(10)
                    ->autosize()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query( Product::latest() )
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori'),
                ImageColumn::make('photos.photo')
                    ->label('Foto Produk')
                    ->url(fn ($record) => asset('storage/product-photos/' . $record->photo))
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true),
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('size')
                    ->label('Ukuran'),
                TextColumn::make('weight')
                    ->label('Berat Per Kg')
                    ->suffix('kg')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->suffix(' pcs')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus permanen!')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Permanen!')
                    ->modalDescription('Data akan dihapus secara permanen!')
                    ->modalSubmitActionLabel('Ya, hapus permanen!')
                    ->modalCancelActionLabel('Batal')
                    ->action(fn($record) => $record->forceDelete() )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Yakin ingin menghapus produk?')
                        ->modalDescription('Produk yang dihapus akan dipindah ke sampah!')
                        ->modalSubmitActionLabel('Ya, pindahkan!')
                        ->modalCancelActionLabel('Jangan dulu')
                        ->modalIcon('heroicon-o-trash')
                        ->successNotification( fn ($records) =>
                            Notification::make()
                                ->success()
                                ->title('Berhasil dihapus.')
                                ->body('Sebanyak ' . $records->count() . ' data produk berhasil dihapus.')
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->modalHeading('Yakin ingin menghapus produk?')
                        ->modalDescription('Produk akan dihapus secara permanen dan tidak dapat dipulihkan!')
                        ->modalSubmitActionLabel('Ya, hapus!')
                        ->modalCancelActionLabel('Nanti aja deh')
                        ->modalIcon('heroicon-o-no-symbol')
                        ->successNotification( fn ($records) =>
                            Notification::make()
                                ->success()
                                ->title('Dihapus Permanen.')
                                ->body('Sebanyak ' . $records->count() . ' data produk berhasil dihapus secara permanen.')
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->modalHeading('Yakin ingin memulihkan produk?')
                        ->modalDescription('Produk yang dipulihkan dapat dilihat kembali di data produk.')
                        ->modalSubmitActionLabel('Ya, pulihkan!')
                        ->modalCancelActionLabel('Batal')
                        ->modalIcon('heroicon-o-arrow-left-start-on-rectangle')
                        ->successNotification( fn ($records) =>
                            Notification::make()
                                ->success()
                                ->title('Dipulihkan.')
                                ->body('Sebanyak ' . $records->count() . ' data produk berhasil dipulihkan.')
                        ),
                ]),
            ])
            ->searchPlaceholder('Cari nama produk');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
