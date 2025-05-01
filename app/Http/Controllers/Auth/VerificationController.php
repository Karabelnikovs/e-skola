<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Routing\Controller;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    protected function redirectTo()
    {
        $lang = auth()->user()->main_lang ?? 'en';
        return route($lang . '.courses.index');
    }
}