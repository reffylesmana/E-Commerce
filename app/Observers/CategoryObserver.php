<?php

namespace App\Observers;

use App\Models\Category;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->notify(
            title: 'Kategori Dibuat 🎉',
            body: "Kategori '{$category->name}' berhasil dibuat!",
            color: 'success',
            icon: 'heroicon-o-tag'
        );
    }

    public function updated(Category $category): void
    {
        $this->notify(
            title: 'Kategori Diupdate ✏️',
            body: "Kategori '{$category->name}' berhasil diupdate!",
            color: 'warning',
            icon: 'heroicon-o-pencil'
        );
    }

    public function deleted(Category $category): void
    {
        $this->notify(
            title: 'Kategori Dihapus 🗑️',
            body: "Kategori '{$category->name}' berhasil dihapus!",
            color: 'danger',
            icon: 'heroicon-o-trash'
        );
    }

    // Fungsi notify untuk mengirim notifikasi
    private function notify(string $title, string $body, string $color, string $icon): void
    {
        $user = auth()->user(); // Mendapatkan user yang sedang login
        if ($user) {
            Notification::make()
                ->title($title)
                ->body($body)
                ->color($color)
                ->icon($icon)
                ->actions([
                    Action::make('Lihat')
                        ->icon('heroicon-s-arrow-right')
                ])
                ->sendToDatabase($user); // Mengirim notifikasi ke database
        } else {
            \Log::error('User  not authenticated for notifications.'); // Log error jika user tidak terautentikasi
        }
    }
}