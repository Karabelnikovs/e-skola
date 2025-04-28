<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AdminCheck
{
    public function handle($request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('error')->with('reason', 'reason2');
        }


        return $next($request);
    }
}

