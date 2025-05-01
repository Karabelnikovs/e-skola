<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
class LoginController extends Controller
{

    public function home()
    {
        $lang = session('lang', 'lv');
        if (!auth()->check()) {
            return redirect('/' . $lang . '/login');
        } else {
            return redirect('/' . $lang);
        }
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            Session::put('is_admin', true);
        } else {
            Session::put('is_admin', false);
        }
    }

    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect('/' . session('lang', 'lv'));
        }
        $lang = session('lang', 'lv');
        $titles = [
            'lv' => 'Pieslēgties',
            'en' => 'Login',
            'ru' => 'Вход',
            'ukr' => 'Увійти',
        ];
        $title = $titles[$lang] ?? 'Login';
        return view('auth.login', compact('title'));
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->role === 'admin') {
                session(['is_admin' => true]);
            } else {
                session(['is_admin' => false]);
            }

            return redirect()->intended('/' . session('lang', 'lv'));
        }


        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',

        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::forget('is_admin');
        return redirect('/');
    }
}