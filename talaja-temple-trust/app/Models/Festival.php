<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'temple_id', 'title', 'title_gu', 'description', 'start_date', 'end_date',
        'image_path', 'is_active',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_active' => 'boolean'];
}
