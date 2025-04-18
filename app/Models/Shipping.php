<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'order_id',
        'tracking_number',
        'shipping_method',
        'status',
        'shipped_at',
        'delivered_at',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the order that owns the shipping.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

