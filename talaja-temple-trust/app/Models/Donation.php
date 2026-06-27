<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Donation extends Model
{
    protected $fillable = [
        'receipt_no', 'temple_id', 'donor_id', 'donation_category_id', 'amount', 'currency',
        'payment_mode', 'status', 'gateway', 'gateway_transaction_id', 'is_80g',
        'donor_name', 'donor_email', 'donor_mobile', 'donor_pan', 'donor_address',
        'is_anonymous', 'note', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2', 'is_80g' => 'boolean', 'is_anonymous' => 'boolean', 'paid_at' => 'datetime',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(DonationReceipt::class);
    }

    public function receiptsMorph(): MorphMany
    {
        return $this->morphMany(Receipt::class, 'receiptable');
    }
}
