<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $fillable = [
        'title_lv',
        'title_en',
        'title_ru',
        'title_ua',
        'content_lv',
        'content_en',
        'content_ru',
        'content_ua',
        'image_path',
        'course_id',
    ];
}
