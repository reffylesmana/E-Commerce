<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductPhoto extends Model
{

    protected $fillable = ['photo', 'product_id'];
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($productPhoto) {
            Storage::disk('public')->delete($productPhoto->photo);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
