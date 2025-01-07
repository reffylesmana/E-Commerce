<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class categoryPhoto extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($categoryPhoto) {
            Storage::disk('public')->delete($categoryPhoto->photo);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }
}
