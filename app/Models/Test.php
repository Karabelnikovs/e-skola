<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    protected $fillable = [
        'title_en',
        'title_lv',
        'title_ru',
        'title_ua',
        'passing_score',
        'order',
        'course_id',
        'type',
    ];

}
