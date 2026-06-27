<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory, HasTranslationFallback;
    protected $fillable = ['room_type_id', 'number', 'floor', 'housekeeping_status', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function isAvailable(string $checkIn, string $checkOut, ?int $excludeBookingId = null): bool
    {
        return ! self::where('id', $this->id)
            ->whereHas('bookings', function ($q) use ($checkIn, $checkOut, $excludeBookingId) {
                $q->where('status', '!=', 'cancelled')
                    ->where(fn ($b) => $b->where('check_out', '>', $checkIn)->where('check_in', '<', $checkOut))
                    ->when($excludeBookingId, fn ($b) => $b->where('id', '!=', $excludeBookingId));
            })
            ->exists();
    }
}
