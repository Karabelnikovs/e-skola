<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeEmail extends Model
{

    public $table = 'welcome_email';
    protected $fillable = [
        'content_en',
        'content_lv',
        'content_ua',
        'content_ru',
    ];
}
