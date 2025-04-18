<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('markAllAsRead')
                ->label('Tandai Semua Dibaca')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function () {
                    $unreadNotifications = \Illuminate\Notifications\DatabaseNotification::whereNull('read_at')->get();
                    foreach ($unreadNotifications as $notification) {
                        $notification->markAsRead();
                    }
                    
                    $this->notify('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
                }),
        ];
    }
}
