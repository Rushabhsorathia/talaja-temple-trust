<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'slug', 'name', 'name_gu', 'description', 'description_gu', 'image_path',
        'price', 'compare_at_price', 'stock', 'category', 'is_active',
    ];

    protected $casts = ['price' => 'decimal:2', 'compare_at_price' => 'decimal:2', 'stock' => 'integer', 'is_active' => 'boolean'];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
