<?php

namespace App\Filament\Resources\StoreBannerResource\Pages;

use App\Filament\Resources\StoreBannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreBanners extends ListRecords
{
    protected static string $resource = StoreBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
