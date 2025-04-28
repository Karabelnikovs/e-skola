<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('error')->with('reason', 'reason1');
        }


        return $next($request);
    }
}