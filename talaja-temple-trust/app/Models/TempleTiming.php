<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempleTiming extends Model
{
    use HasTranslationFallback;

    protected $table = 'temple_timings';

    protected $fillable = [
        'temple_id', 'type', 'title', 'title_gu', 'start_time', 'end_time',
        'day_of_week', 'fee', 'is_active', 'sort_order',
    ];

    protected $casts = ['fee' => 'decimal:2', 'is_active' => 'boolean', 'start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i'];

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }
}
