<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);
        $supported = config('locale.supported', ['lv', 'en', 'ru', 'ukr']);



        if (in_array($locale, $supported)) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
        } else {
            $defaultLocale = 'lv';
            Session::put('lang', $defaultLocale);
            Session::save();
            return redirect('/' . $defaultLocale . $request->getPathInfo());
        }

        Log::info('SetLocale completed', [
            'locale' => $locale,
            'session_lang' => Session::get('lang'),
            'request_uri' => $request->getRequestUri(),
        ]);

        return $next($request);
    }
}