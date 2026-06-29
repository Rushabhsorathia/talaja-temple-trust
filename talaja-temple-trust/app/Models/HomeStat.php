<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class HomeStat extends Model
{
    use HasTranslationFallback;

    protected $fillable = ['value', 'label', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
