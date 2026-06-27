<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveDarshanConfig extends Model
{
    protected $table = 'live_darshan_config';

    protected $fillable = ['temple_id', 'stream_url', 'is_live', 'poster_path', 'start_time', 'end_time'];

    protected $casts = ['is_live' => 'boolean', 'start_time' => 'datetime:H:i', 'end_time' => 'datetime:H:i'];

    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }
}
