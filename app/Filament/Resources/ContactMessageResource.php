<?php
// app/Filament/Resources/ContactMessageResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $modelLabel = 'Pesan Kontak';
    protected static ?string $navigationLabel = 'Pesan Masuk';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengirim')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Pesan')
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('Sudah Dibaca')
                            ->inline(false),
                        Forms\Components\Textarea::make('message')
                            ->label('Isi Pesan')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->disabled(fn (string $context) => $context === 'view'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unread')
                    ->label('Belum Dibaca')
                    ->query(fn ($query) => $query->where('is_read', false)),
                
                Tables\Filters\SelectFilter::make('is_read')
                    ->label('Status')
                    ->options([
                        '1' => 'Sudah Dibaca',
                        '0' => 'Belum Dibaca',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->after(function (\App\Models\ContactMessage $record) {
                    if (! $record->is_read) {
                        $record->update(['is_read' => 1]);
                    }
                }),
            
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}