<?php

namespace App\Models;

use App\Models\Concerns\HasTranslationFallback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Temple extends Model implements HasMedia
{
    use HasFactory, HasTranslationFallback, InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'is_primary', 'address', 'phone', 'email',
        'map_embed', 'logo_path', 'is_active',
    ];

    protected $casts = ['is_primary' => 'boolean', 'is_active' => 'boolean'];

    public function translations(): HasMany
    {
        return $this->hasMany(TempleTranslation::class);
    }

    public function translation(?string $locale = null): HasOne
    {
        return $this->hasOne(TempleTranslation::class)->where('locale', $locale ?? app()->getLocale());
    }

    public function timings(): HasMany
    {
        return $this->hasMany(TempleTiming::class);
    }

    public function festivals(): HasMany
    {
        return $this->hasMany(Festival::class);
    }
}
