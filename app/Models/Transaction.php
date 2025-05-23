<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_amount',
        'shipping_cost',
        'tax_amount',
        'status',
        'notes',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the transaction.
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'transaction_id', 'id');
    }

    /**
     * Get the payments for the transaction.
     */    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}

