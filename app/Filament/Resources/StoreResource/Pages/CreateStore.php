<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use App\Models\Store;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function handleRecordCreation(array $data): Store
    {
        $store = Store::create($data);
    
        // Pastikan key 'categories' ada sebelum digunakan
        if (isset($data['categories']) && is_array($data['categories'])) {
            $store->categories()->sync($data['categories']);
        }
    
        return $store;
    }
}
