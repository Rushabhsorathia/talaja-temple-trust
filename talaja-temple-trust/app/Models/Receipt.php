<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_no', 'temple_id', 'source', 'receiptable_id', 'receiptable_type',
        'amount', 'payment_mode', 'date', 'note',
    ];

    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }

    public function receiptable(): MorphTo
    {
        return $this->morphTo();
    }
}
