<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'type', 'name', 'email', 'mobile', 'category', 'rating', 'message',
        'status', 'assigned_to', 'admin_reply',
    ];

    protected $casts = ['rating' => 'integer'];
}
