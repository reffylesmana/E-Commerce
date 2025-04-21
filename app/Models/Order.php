<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transaction_id', 'order_number', 'total_amount', 'shipping_address', 'shipping_method', 'status', 'notes'];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction that owns the order.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the shipping information for the order.
     */
    public function shipping(): HasOne
    {
        return $this->hasOne(Shipping::class);
    }

    /**
     * Get the payment record associated with the order.
     */
    public function payment()
    {
        return $this->hasOneThrough(
            Payment::class,
            Transaction::class,
            'id', 
            'transaction_id', 
            'transaction_id', 
            'id' 
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')->where('created_at', '<', now()->subDay());
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

    // Tambahkan di properti model
    protected $dates = ['created_at'];
}
