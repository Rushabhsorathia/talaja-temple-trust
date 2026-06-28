<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = ['group', 'key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group, 'type' => $type]);
    }

    public static function byGroup(string $group)
    {
        return static::where('group', $group)->orderBy('key')->get()->pluck('value', 'key');
    }
}