<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $label = 'Produk';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Produk'),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('price')->label('Harga'),
                Tables\Columns\TextColumn::make('stock')->label('Stok'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // Filter untuk menampilkan data yang dihapus
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Hanya untuk melihat detail produk
                Tables\Actions\DeleteAction::make(), // Untuk menghapus produk
            ])
            ->bulkActions([]); // Kosongkan bulk actions
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'), // Halaman untuk mengelola produk
        ];
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('id')->label('ID')->disabled(),
            Forms\Components\TextInput::make('category_id')->label('Category ID')->disabled(),
            Forms\Components\TextInput::make('user_id')->label('User  ID')->disabled(),
            Forms\Components\TextInput::make('slug')->label('Slug')->disabled(),
            Forms\Components\TextInput::make('name')->label('Nama Produk')->disabled(),
            Forms\Components\TextInput::make('price')->label('Harga')->disabled(),
            Forms\Components\Textarea::make('description')->label('Deskripsi')->disabled(),
            Forms\Components\TextInput::make('size')->label('Ukuran')->disabled(),
            Forms\Components\TextInput::make('weight')->label('Berat')->disabled(),
            Forms\Components\TextInput::make('stock')->label('Stok')->disabled(),
            Forms\Components\TextInput::make('deleted_at')->label('Dihapus Pada')->disabled(),
            Forms\Components\TextInput::make('created_at')->label('Dibuat Pada')->disabled(),
            Forms\Components\TextInput::make('updated_at')->label('Diperbarui Pada')->disabled(),
            Forms\Components\TextInput::make('store_id')->label('Store ID')->disabled(),
        ];
    }
}