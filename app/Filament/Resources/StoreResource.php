<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront'; 
    protected static ?string $navigationLabel = 'Toko';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Store Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Store Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->label('Description')
                            ->disabled(),

                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->directory('store-logos')
                            ->label('Store Logo')
                            ->disabled()
                            ->visibility('public'),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Owner')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Approval Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->required()
                            ->label('Approved')
                            ->helperText('Approve this store to allow it to operate on the platform')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                if (!$state) {
                                    $set('is_official', false);
                                    $set('is_active', false);
                                }
                            }),

                        Forms\Components\Toggle::make('is_official')
                            ->required()
                            ->label('Official Store')
                            ->helperText('Mark this store as an official partner')
                            ->onIcon('heroicon-o-check') 
                            ->offIcon('heroicon-o-x-mark')
                            ->disabled(fn ($get) => !$get('is_approved')),

                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->label('Active')
                            ->helperText('Deactivate to temporarily suspend this store')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->disabled(fn ($get) => !$get('is_active')),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Categories')
                    ->schema([
                        Forms\Components\MultiSelect::make('categories')
                            ->relationship('categories', 'name')
                            ->label('Store Categories')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record): string => 
                        $record->logo 
                            ? asset('storage/' . $record->logo) 
                            : asset('images/placeholder-store.png')
                    )
                    ->label('Logo'),
                
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Store Name')
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Owner'),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('categories.name')
                    ->badge()
                    ->label('Categories'),

                IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved')
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('is_official')
                    ->boolean()
                    ->label('Official')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Created')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->deferLoading()
            ->persistSortInSession()
            ->persistSearchInSession()
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending Approval',
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'] === 'approved', function ($query) {
                            return $query->where('is_approved', true);
                        })->when($data['value'] === 'pending', function ($query) {
                            return $query->where('is_approved', false);
                        });
                    }),

                Tables\Filters\Filter::make('is_official')
                    ->query(fn ($query) => $query->where('is_official', true))
                    ->label('Official Stores')
                    ->toggle(),

                Tables\Filters\Filter::make('is_active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Active Stores')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                
                Action::make('quick_approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_approved)
                    ->action(function (Store $record) {
                        $record->update(['is_approved' => true]);
                        Notification::make()
                            ->title('Store approved successfully')
                            ->success()
                            ->send();
                    }),
                
                Action::make('quick_deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->is_active)
                    ->requiresConfirmation()
                    ->action(function (Store $record) {
                        $record->update(['is_active' => false]);
                        Notification::make()
                            ->title('Store deactivated')
                            ->warning()
                            ->send();
                    }),
                
                Action::make('quick_activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_active)
                    ->action(function (Store $record) {
                        $record->update(['is_active' => true]);
                        Notification::make()
                            ->title('Store activated')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('approve_selected')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['is_approved' => true]);
                        });
                        Notification::make()
                            ->title('Selected stores approved')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\BulkAction::make('deactivate_selected')
                    ->label('Deactivate Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update(['is_active' => false]);
                        });
                        Notification::make()
                            ->title('Selected stores deactivated')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}