<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'voucher_no', 'temple_id', 'payee', 'category', 'amount', 'payment_mode',
        'date', 'approved_by', 'note',
    ];

    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];
}
