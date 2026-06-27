<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = ['code', 'channel', 'subject', 'body', 'body_gu', 'variables', 'is_active'];

    protected $casts = ['variables' => 'array', 'is_active' => 'boolean'];

    public function localizedBody(): string
    {
        $locale = app()->getLocale();

        return filled($this->body_gu) && $locale === 'gu' ? $this->body_gu : $this->body;
    }
}
