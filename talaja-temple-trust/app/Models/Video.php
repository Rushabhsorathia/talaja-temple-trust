<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'title', 'title_gu', 'source', 'source_id', 'thumbnail_path', 'category', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getEmbedUrlAttribute(): string
    {
        return $this->source === 'youtube'
            ? "https://www.youtube.com/embed/{$this->source_id}"
            : "https://player.vimeo.com/video/{$this->source_id}";
    }
}
