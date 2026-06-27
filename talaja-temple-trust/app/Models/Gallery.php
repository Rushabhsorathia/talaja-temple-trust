<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'title', 'title_gu', 'image_path', 'alt_text', 'category', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
