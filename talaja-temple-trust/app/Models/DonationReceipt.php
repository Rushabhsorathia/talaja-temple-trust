<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationReceipt extends Model
{
    protected $fillable = ['donation_id', 'receipt_no', 'receipt_type', 'pdf_path', 'is_void'];

    protected $casts = ['is_void' => 'boolean'];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }
}
