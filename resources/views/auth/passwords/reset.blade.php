@extends('layouts.guest')
@section('content')
    @php
        $translations = [
            'en' => [
                'email' => 'Email',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'reset_password' => 'Reset Password',
                'confirm_error' => 'The password confirmation does not match.',
                'min_string_error' => 'The password must be at least 8 characters.',
                'error' => 'Error',
            ],
            'lv' => [
                'email' => 'E-pasts',
                'password' => 'Parole',
                'confirm_password' => 'Apstiprināt paroli',
                'reset_password' => 'Atiestatīt paroli',
                'confirm_error' => 'Paroles apstiprinājums nesakrīt.',
                'min_string_error' => 'Parolei jābūt vismaz 8 rakstzīmēm garai.',
                'error' => 'Kļūda',
            ],
            'ru' => [
                'email' => 'Электронная почта',
                'password' => 'Пароль',
                'confirm_password' => 'Подтвердить пароль',
                'reset_password' => 'Сбросить пароль',
                'confirm_error' => 'Подтверждение пароля не совпадает.',
                'min_string_error' => 'Пароль должен содержать не менее 8 символов.',
                'error' => 'Ошибка',
            ],
            'ua' => [
                'email' => 'Електронна пошта',
                'password' => 'Пароль',
                'confirm_password' => 'Підтвердити пароль',
                'reset_password' => 'Скинути пароль',
                'confirm_error' => 'Підтвердження пароля не збігається.',
                'min_string_error' => 'Пароль повинен містити не менше 8 символів.',
                'error' => 'Помилка',
            ],
        ];
        $lang = Session::get('lang', 'en');
    @endphp

    <span class="text-2xl font-light">
        <svg xmlns="http://www.w3.org/2000/svg" width="83" height="36" class="mb-4" viewBox="0 0 83 36" fill="none">
            <path d="M0 12.4503H4.94253L11.9024 28.3754L18.8623 12.4503H23.704L13.4911 35.7504H10.2129L0 12.4503Z"
                fill="#36225f"></path>
            <path d="M27.2598 12.4503H32.0006V35.7504H27.2598V12.4503Z" fill="#36225f"></path>
            <path
                d="M49.8794 16.6503H37.7248V12.4503H56.4862V15.4253L43.5247 31.5254H57.4697V35.7504H36.9683V32.7754L49.8794 16.6503Z"
                fill="#36225f"></path>
            <path d="M62.0591 12.4503H66.7999V35.7504H62.0591V12.4503Z" fill="#36225f"></path>
            <path d="M73.1797 12.4503H77.9205V35.7504H73.1797V12.4503Z" fill="#36225f"></path>
            <path
                d="M75.626 0C74.5164 1.95 72.4234 3.275 70.0026 3.275C67.5817 3.275 65.4635 1.95 64.3792 0L60.4453 2.25C62.3618 5.525 65.9174 7.75 70.0278 7.75C74.1129 7.75 77.6938 5.55 79.6103 2.25L75.626 0Z"
                fill="#36225f"></path>
        </svg>
    </span>
    <div class="mt-4 bg-white shadow-md rounded-lg text-left">
        <form method="POST" action="{{ route($lang . '.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="h-2 bg-purple-400 rounded-t-md"></div>
            <div class="px-8 py-6">
                @if ($errors->any())
                    @if (collect($errors->all())->contains(__('validation.min.string')))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: `{{ $translations[$lang]['error'] }}!`,
                                html: `{{ $translations[$lang]['min_string_error'] }}`
                            });
                        </script>
                    @elseif (collect($errors->all())->contains(__('validation.confirmed')))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: `{{ $translations[$lang]['error'] }}!`,
                                html: `{{ $translations[$lang]['confirm_error'] }}`
                            });
                        </script>
                    @else
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: `{{ $translations[$lang]['error'] }}!`,
                                html: `{!! implode('<br>', $errors->all()) !!}`
                            });
                        </script>
                    @endif
                @endif

                <h1 class="text-xl font-semibold">{{ $translations[$lang]['reset_password'] }}</h1>

                <label class="block mt-3 font-semibold">{{ $translations[$lang]['email'] }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                <label class="block mt-3 font-semibold">{{ $translations[$lang]['password'] }}</label>
                <input type="password" name="password" required
                    class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                <label class="block mt-3 font-semibold">{{ $translations[$lang]['confirm_password'] }}</label>
                <input type="password" name="password_confirmation" required
                    class="border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-indigo-500 focus:ring-1 rounded-md">

                <button type="submit" class="mt-4 bg-purple-500 text-white py-2 px-6 rounded-md hover:bg-purple-600">
                    {{ $translations[$lang]['reset_password'] }}
                </button>
            </div>
        </form>
    </div>
@endsection
