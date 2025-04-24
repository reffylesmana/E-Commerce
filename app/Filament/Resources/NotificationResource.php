<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class NotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationGroup = 'Sistem';
    
    protected static ?string $navigationLabel = 'Notifikasi';
    
    protected static ?string $pluralModelLabel = 'Notifikasi';
    
    protected static ?string $modelLabel = 'Notifikasi';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('notifiable_type')
                    ->label('Tipe Penerima')
                    ->options([
                        'App\Models\User' => 'User ',
                    ])
                    ->required()
                    ->disabled(),
                    
                Forms\Components\Select::make('notifiable_id')
                    ->label('Penerima')
                    ->options(function () {
                        return User::all()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->disabled(),
                    
                Forms\Components\TextInput::make('type')
                    ->label('Tipe Notifikasi')
                    ->required()
                    ->disabled(),
                    
                Forms\Components\KeyValue::make('data')
                    ->label('Data')
                    ->disabled(),
                    
                Forms\Components\DateTimePicker::make('read_at')
                    ->label('Dibaca Pada')
                    ->disabled(),
                    
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Dibuat Pada')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('notifiable.name')
                    ->label('Penerima')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('data.title')
                    ->label('Judul')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('data.message')
                    ->label('Pesan')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('read_at')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn (DatabaseNotification $record): bool => $record->read_at !== null)
                    ->label('Dibaca'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('read')
                    ->label('Status Dibaca')
                    ->options([
                        'read' => 'Dibaca',
                        'unread' => 'Belum Dibaca',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'read') {
                            return $query->whereNotNull('read_at');
                        }
                        
                        if ($data['value'] === 'unread') {
                            return $query->whereNull('read_at');
                        }
                        
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('markAsRead')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (DatabaseNotification $record): bool => $record->read_at === null)
                    ->action(function (DatabaseNotification $record) {
                        $record->markAsRead();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Tandai Dibaca')
                        ->icon('heroicon-o-check')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->read_at === null) {
                                    $record->markAsRead();
                                }
                            }
                        }),
                ]),
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
            'index' => Pages\ListNotifications::route('/'),
            'view' => Pages\ViewNotification::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Filament::auth()->user();
        
        return parent::getEloquentQuery()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', $user::class)
            ->latest();
    }
}