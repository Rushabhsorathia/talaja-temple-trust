<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBooking extends Model
{
    protected $fillable = [
        'booking_no', 'room_id', 'user_id', 'guest_name', 'guest_email', 'guest_mobile',
        'guests', 'check_in', 'check_out', 'amount', 'payment_mode', 'status',
        'gateway_transaction_id', 'checked_in_at', 'checked_out_at', 'note',
    ];

    protected $casts = [
        'check_in' => 'date', 'check_out' => 'date', 'amount' => 'decimal:2',
        'guests' => 'integer', 'checked_in_at' => 'datetime', 'checked_out_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
