<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasTranslationFallback;

    protected $fillable = [
        'question', 'question_gu', 'answer', 'answer_gu', 'category', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
