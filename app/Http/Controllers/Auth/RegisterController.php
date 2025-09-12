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
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\WelcomeEmail;
use PhpParser\Node\Stmt\Switch_;
use App\Models\PendingUser;

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
            'main_lang' => ['required', 'string', 'in:en,lv,ru,ua'],
        ]);
    }

    public function showRegistrationForm()
    {
        // dd('showRegistrationForm');

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'language' => $data['main_lang'],
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            logger()->error('Failed to send welcome email: ' . $e->getMessage());
            $user->delete();
            $lang = $user->language ?? 'lv';
            switch ($lang) {
                case 'ua':
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'bad_email' => 'Електронна адреса недійсна або не може отримувати електронні листи. Спробуйте іншу.'
                    ]);
                case 'ru':
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'bad_email' => 'Электронная почта недействительна или не может получать электронные письма. Попробуйте другой.'
                    ]);
                case 'lv':
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'bad_email' => 'E-pasts ir nederīgs vai nevar saņemt e-pastus. Mēģiniet citu.'
                    ]);
                default:
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'bad_email' => 'Email is invalid or cannot receive emails. Try a different one.'
                    ]);
            }


        }

        return $user;
    }



    protected function registered(Request $request, $user)
    {
        if ($request->filled('token')) {
            $pending = PendingUser::where('token', $request->input('token'))
                ->where('email', $user->email)
                ->first();

            if ($pending) {
                $pending->delete();
            }
        }

        Session::put('lang', $user->language);
        $credentials = $user->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->role === 'admin') {
                session(['is_admin' => true]);
            } else {
                session(['is_admin' => false]);
            }

            return redirect()->intended('/' . session('lang', 'lv'));
        }
        $lang = $user->language ?? 'lv';
        return redirect('/' . $lang);
    }

    public function showInvitedRegistrationForm($token)
    {
        $pending = PendingUser::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        session(['lang' => $pending->language]);
        $lang = $pending->language;

        $translations = [
            'lv' => 'Reģistrācija',
            'en' => 'Registration',
            'ru' => 'Регистрация',
            'ukr' => 'Реєстрація',
        ];
        $title = $translations[$lang] ?? 'Registration';
        $prefill = $pending;

        return view('auth.register', compact('title', 'prefill'));
    }
}