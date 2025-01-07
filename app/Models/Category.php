<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    //

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            // Hapus file foto kategori
            if ($category->photo) {
                Storage::disk('public')->delete($category->photo); // Menghapus file dari public/storage
            }
        });
        static::updating(function ($category) {
            if ($category->isDirty('photo')) {
                $oldPhoto = $category->getOriginal('photo'); // Foto lama sebelum update
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto); // Hapus foto lama
                }
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
