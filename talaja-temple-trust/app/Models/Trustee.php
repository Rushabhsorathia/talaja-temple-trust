<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Trustee extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'temple_id', 'name', 'designation', 'designation_gu', 'bio', 'bio_gu',
        'photo_path', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
