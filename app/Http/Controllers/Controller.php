<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

abstract class Controller
{
    public function __construct()
    {
        $locale = request()->segment(1);
        $supportedLocales = ['lv', 'en', 'ru', 'ua'];

        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            session(['lang' => $locale]);
        }
    }

}
