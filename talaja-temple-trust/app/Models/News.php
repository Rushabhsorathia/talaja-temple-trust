<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasTranslationFallback;

    protected $table = 'news';

    protected $fillable = [
        'slug', 'title', 'title_gu', 'excerpt', 'excerpt_gu', 'content', 'content_gu',
        'image_path', 'category', 'tags', 'meta_title', 'meta_description',
        'is_published', 'published_at',
    ];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime', 'tags' => 'array'];

    protected static function booted(): void
    {
        static::creating(function (self $news) {
            $news->slug ??= Str::slug($news->title).'-'.Str::random(5);
            $news->published_at ??= now();
        });
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
            ->where(fn ($qq) => $qq->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at');
    }
}
