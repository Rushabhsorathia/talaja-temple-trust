<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BankStatement extends Model
{
    protected $fillable = [
        'temple_id', 'date', 'description', 'debit', 'credit', 'balance',
        'reference', 'reconciliation_status', 'reconcilable_id', 'reconcilable_type',
    ];

    protected $casts = ['date' => 'date', 'debit' => 'decimal:2', 'credit' => 'decimal:2', 'balance' => 'decimal:2'];

    public function reconcilable(): MorphTo
    {
        return $this->morphTo();
    }
}
