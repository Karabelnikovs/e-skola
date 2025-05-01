<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{

    protected $table = 'privacy';
    protected $fillable = [
        'content_en',
        'content_lv',
        'content_ua',
        'content_ru',
    ];
}
