<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_lv',
        'question_en',
        'question_ru',
        'question_uk',
        'options_lv',
        'options_en',
        'options_ru',
        'options_ua',
        'test_id',
        'correct_answer',
        'order',
    ];
}
