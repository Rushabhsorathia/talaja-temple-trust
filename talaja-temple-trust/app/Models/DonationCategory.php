<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCategory extends Model
{
    use HasFactory, HasTranslationFallback;
    protected $fillable = ['name', 'name_gu', 'description', 'is_80g_eligible', 'is_active'];

    protected $casts = ['is_80g_eligible' => 'boolean', 'is_active' => 'boolean'];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
