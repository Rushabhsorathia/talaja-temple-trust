<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory, HasTranslationFallback;

    protected $fillable = [
        'temple_id', 'name', 'name_gu', 'description', 'tariff', 'capacity', 'amenities', 'is_active',
    ];

    protected $casts = ['tariff' => 'decimal:2', 'capacity' => 'integer', 'amenities' => 'array', 'is_active' => 'boolean'];

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
