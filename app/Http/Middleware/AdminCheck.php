<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheck
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('error')->with('reason', 'reason2');
        }

        return $next($request);
    }
}
