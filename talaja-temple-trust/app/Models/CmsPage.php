<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasTranslationFallback;

    protected $table = 'cms_pages';

    protected $fillable = [
        'slug', 'title', 'title_gu', 'content', 'content_gu',
        'meta_title', 'meta_description', 'meta_image',
        'is_published', 'sort_order',
    ];

    protected $casts = ['is_published' => 'boolean'];
}
