<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingHall extends Model
{
    use HasTranslationFallback;

    protected $fillable = ['temple_id', 'name', 'name_gu', 'capacity', 'tariff', 'amenities', 'is_active'];

    protected $casts = ['capacity' => 'integer', 'tariff' => 'decimal:2', 'amenities' => 'array', 'is_active' => 'boolean'];

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }
}
