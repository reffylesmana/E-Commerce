<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAsRead')
                ->label('Tandai Dibaca')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => $this->record->read_at === null)
                ->action(function () {
                    $this->record->markAsRead();
                    $this->notify('success', 'Notifikasi telah ditandai sebagai dibaca.');
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
