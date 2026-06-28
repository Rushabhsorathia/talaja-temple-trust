<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'slug', 'title', 'title_gu', 'route_name',
        'meta_title', 'meta_description', 'meta_image', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function activeSections(): HasMany
    {
        return $this->sections()->where('is_active', true);
    }

    public function section(string $key)
    {
        return $this->sections()->where('section_key', $key)->first();
    }
}