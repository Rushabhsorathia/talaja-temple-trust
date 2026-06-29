<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasTranslationFallback;

    protected $fillable = ['icon', 'title', 'description', 'image_path', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
