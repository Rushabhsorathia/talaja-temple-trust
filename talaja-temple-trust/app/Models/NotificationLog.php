<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    protected $fillable = [
        'channel', 'recipient', 'template_id', 'content', 'status',
        'provider_message_id', 'error', 'notifiable_id', 'notifiable_type', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
