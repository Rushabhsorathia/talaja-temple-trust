<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Adds a localized accessor so a model can return the field for the active
 * locale, falling back to the base field. E.g. $new->title_gu.
 */
trait HasTranslationFallback
{
    public function localized(string $baseField): ?string
    {
        $locale = app()->getLocale();
        $localizedField = "{$baseField}_{$locale}";

        return filled($this->{$localizedField}) ? $this->{$localizedField} : $this->{$baseField};
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('is_active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
