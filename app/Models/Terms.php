<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{

    protected $fillable = [
        'content_en',
        'content_lv',
        'content_ua',
        'content_ru',
    ];
}
