<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_id',
        'payment_method',
        'amount',
        'status',
        'payment_details',
        'notes',
    ];

    protected $casts = [
        'payment_details' => 'array',
    ];

    /**
     * Get the transaction that owns the payment.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    
}

