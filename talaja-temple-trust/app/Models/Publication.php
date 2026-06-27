<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'title', 'title_gu', 'file_path', 'category', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
