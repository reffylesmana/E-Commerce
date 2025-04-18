<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'subtotal', 'seller_id'];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that belongs to the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Model OrderItem
    public function productPhotos()
    {
        return $this->product->photos; // Mendapatkan koleksi photos langsung dari relasi
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
