<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleTranslation extends Model
{
    protected $fillable = ['temple_id', 'locale', 'history', 'about_trust', 'trust_info'];
}
