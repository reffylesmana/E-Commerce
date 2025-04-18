<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $label = 'Kategori';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 2;
    
    // Fix the route key
    protected static ?string $recordRouteKeyName = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('photo')
                    ->required()
                    ->image()
                    ->visibility('public') 
                    ->maxSize(1024)
                    ->directory('category-photos')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Category::query()->orderBy('created_at', 'desc')) 
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto Kategori')
                    ->getStateUsing(fn ($record) => $record->photo ? asset('storage/' . $record->photo) : asset('path/to/default/category.png'))
                    ->rounded(), 
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(), 
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Yakin ingin menghapus kategori?')
                        ->modalDescription('kategori yang dihapus akan dipindah ke sampah!')
                        ->modalSubmitActionLabel('Ya, pindahkan!')
                        ->modalCancelActionLabel('Jangan dulu')
                        ->modalIcon('heroicon-o-trash')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil Dihapus.')
                                ->body(fn ($records) => 'Sebanyak ' . $records->count() . ' kategori berhasil dipindahkan ke sampah.')
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->modalHeading('Yakin ingin memulihkan kategori?')
                        ->modalDescription('kategori yang dipulihkan dapat dilihat kembali di data produk.')
                        ->modalSubmitActionLabel('Ya, pulihkan!')
                        ->modalCancelActionLabel('Batal')
                        ->modalIcon('heroicon-o-arrow-left-start-on-rectangle')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Dipulihkan.')
                                ->body(fn ($records) => 'Sebanyak ' . $records->count() . ' kategori berhasil dipulihkan.')
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}

