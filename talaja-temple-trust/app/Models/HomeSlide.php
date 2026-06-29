<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'image_path', 'title', 'subtitle', 'tag', 'button_label', 'button_href',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
