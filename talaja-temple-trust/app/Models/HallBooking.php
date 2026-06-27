<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HallBooking extends Model
{
    protected $fillable = [
        'booking_no', 'meeting_hall_id', 'user_id', 'guest_name', 'guest_mobile',
        'event_date', 'start_time', 'end_time', 'attendees', 'amount', 'status', 'note',
    ];

    protected $casts = ['event_date' => 'date', 'attendees' => 'integer', 'amount' => 'decimal:2'];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(MeetingHall::class, 'meeting_hall_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
