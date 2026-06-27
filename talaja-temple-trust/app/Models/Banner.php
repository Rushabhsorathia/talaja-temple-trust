<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'title', 'image_path', 'link', 'sort_order', 'publish_at', 'unpublish_at', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'publish_at' => 'datetime', 'unpublish_at' => 'datetime'];

    public function scopeLive($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($qq) => $qq->whereNull('publish_at')->orWhere('publish_at', '<=', now()))
            ->where(fn ($qq) => $qq->whereNull('unpublish_at')->orWhere('unpublish_at', '>=', now()))
            ->orderBy('sort_order');
    }
}
