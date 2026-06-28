<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class HomeService extends Model
{
    use HasTranslationFallback;

    protected $fillable = ['icon', 'title', 'description', 'href', 'badge', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
