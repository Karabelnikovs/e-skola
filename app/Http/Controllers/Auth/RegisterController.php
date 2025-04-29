<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    use RegistersUsers;

    // protected $redirectTo = RouteServiceProvider::HOME;

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'main_lang' => ['required', 'string', 'in:en,lv,ru,ukr'],
        ]);
    }

    public function showRegistrationForm()
    {
        $lang = session('lang', 'en');
        $titles = [
            'lv' => 'Reģistrācija',
            'en' => 'Registration',
            'ru' => 'Регистрация',
            'ukr' => 'Реєстрація',
        ];
        $title = $titles[$lang] ?? 'Registration';
        return view('auth.register', compact('title'));
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'main_lang' => $data['main_lang'],
        ]);
    }

    protected function registered(Request $request, $user)
    {
        Session::put('lang', $user->main_lang);

        return redirect()->route($user->main_lang . '.courses.index', ['lang' => $user->main_lang])->with('success', __('Registration successful!'));
    }
}