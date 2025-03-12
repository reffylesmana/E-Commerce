<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditStore extends EditRecord
{
protected static string $resource = StoreResource::class;

protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('approve')
            ->label('Approve Store')
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(fn ($record) => !$record->is_approved)
            ->action(function () {
                $this->record->update(['is_approved' => true]);
                
                Notification::make()
                    ->title('Store approved successfully')
                    ->success()
                    ->send();
                    
                $this->refreshFormData(['is_approved']);
            }),
            
        Actions\Action::make('toggle_official')
            ->label(fn ($record) => $record->is_official ? 'Remove Official Status' : 'Mark as Official')
            ->icon(fn ($record) => $record->is_official ? 'heroicon-o-star' : 'heroicon-o-star')
            ->color(fn ($record) => $record->is_official ? 'warning' : 'gray')
            ->action(function () {
                $this->record->update(['is_official' => !$this->record->is_official]);
                
                $status = $this->record->is_official ? 'marked as official' : 'unmarked as official';
                
                Notification::make()
                    ->title("Store {$status}")
                    ->success()
                    ->send();
                    
                $this->refreshFormData(['is_official']);
            }),
            
        Actions\Action::make('toggle_active')
            ->label(fn ($record) => $record->is_active ? 'Deactivate Store' : 'Activate Store')
            ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
            ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
            ->requiresConfirmation(fn ($record) => $record->is_active)
            ->action(function () {
                $this->record->update(['is_active' => !$this->record->is_active]);
                
                $status = $this->record->is_active ? 'activated' : 'deactivated';
                
                Notification::make()
                    ->title("Store {$status}")
                    ->success()
                    ->send();
                    
                $this->refreshFormData(['is_active']);
            }),
    ];
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

protected function getSavedNotification(): ?Notification
{
    return Notification::make()
        ->success()
        ->title('Store updated')
        ->body('The store has been updated successfully.');
}



}