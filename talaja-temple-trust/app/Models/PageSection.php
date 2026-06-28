<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    public const TYPES = [
        'hero_slider'  => 'Hero slider',
        'richtext'     => 'Rich text block',
        'stats'        => 'Stats row',
        'cards'        => 'Cards grid',
        'values'       => 'Values / icons',
        'timeline'     => 'Timeline',
        'cta'          => 'Call to action',
        'contact_block'=> 'Contact block',
        'timings'      => 'Timings (darshan/aarti/pooja)',
        'festivals'    => 'Festival calendar',
        'trustees'     => 'Trustees grid',
        'gallery'      => 'Gallery preview',
        'news'         => 'News preview',
        'custom'       => 'Custom JSON',
    ];

    protected $fillable = [
        'page_id', 'section_key', 'type', 'title', 'subtitle', 'content',
        'data', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'data'     => 'array',
        'is_active'=> 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}