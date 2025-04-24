<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages\CreateDiscount;
use App\Filament\Resources\DiscountResource\Pages\EditDiscount;
use App\Filament\Resources\DiscountResource\Pages\ListDiscounts;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $modelLabel = 'Diskon';
    protected static ?string $navigationLabel = 'Diskon';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
                
            Forms\Components\Select::make('type')
                ->required()
                ->options([
                    'percentage' => 'Percentage',
                    'fixed' => 'Fixed',
                ]),
                
            Forms\Components\TextInput::make('value')
                ->required()
                ->numeric()
                ->step(0.01),
                
            Forms\Components\DateTimePicker::make('start_date')
                ->required(),
                
            Forms\Components\DateTimePicker::make('end_date')
                ->required(),
                
            Forms\Components\TextInput::make('max_uses')
                ->numeric()
                ->default(1),
                
            Forms\Components\TextInput::make('used_count')
                ->numeric()
                ->default(0),
                
            Forms\Components\Toggle::make('is_active')
                ->required()
                ->default(true),
                
            Forms\Components\Select::make('store_id')
                ->relationship('store', 'name')
                ->required()
                ->searchable()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type'),
                    
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn (string $state): string => ($state . (static::$model::find($state)?->type === 'percentage' ? '%' : ''))),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscounts::route('/'),
            'create' => CreateDiscount::route('/create'),
            'edit' => EditDiscount::route('/{record}/edit'),
        ];
    }
}