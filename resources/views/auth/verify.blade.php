@extends('layouts.guest')
@section('content')
    @php
        $translations = [
            'en' => [
                'verify_email' => 'Verify Your Email Address',
                'verification_sent' => 'A fresh verification link has been sent to your email address.',
                'check_email' => 'Before proceeding, please check your email for a verification link.',
                'not_received' => 'If you did not receive the email',
                'resend' => 'Click here to request another',
                'error' => 'Error',
            ],
            'lv' => [
                'verify_email' => 'Apstipriniet savu e-pasta adresi',
                'verification_sent' => 'Uz jūsu e-pasta adresi ir nosūtīta jauna apstiprināšanas saite.',
                'check_email' => 'Pirms turpināt, lūdzu, pārbaudiet savu e-pastu, vai tajā nav apstiprināšanas saites.',
                'not_received' => 'Ja jūs nesaņēmāt e-pastu',
                'resend' => 'Noklikšķiniet šeit, lai pieprasītu vēl vienu',
                'error' => 'Kļūda',
            ],
            'ru' => [
                'verify_email' => 'Подтвердите ваш адрес электронной почты',
                'verification_sent' => 'Новая ссылка для подтверждения отправлена на ваш адрес электронной почты.',
                'check_email' => 'Прежде чем продолжить, проверьте свою почту на наличие ссылки для подтверждения.',
                'not_received' => 'Если вы не получили письмо',
                'resend' => 'Нажмите здесь, чтобы запросить еще одно',
                'error' => 'Ошибка',
            ],
            'ua' => [
                'verify_email' => 'Підтвердіть свою електронну адресу',
                'verification_sent' => 'Нове посилання для підтвердження надіслано на вашу електронну адресу.',
                'check_email' => 'Перш ніж продовжити, перевірте свою пошту на наявність посилання для підтвердження.',
                'not_received' => 'Якщо ви не отримали листа',
                'resend' => 'Натисніть тут, щоб запросити ще одне',
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
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: `{{ $translations[$lang]['error'] }}!`,
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif
    <div class="mt-4 bg-white shadow-md rounded-lg text-left">
        <div class="h-2 bg-purple-400 rounded-t-md"></div>
        <div class="px-8 py-6">
            <h1 class="text-xl font-semibold">{{ $translations[$lang]['verify_email'] }}</h1>

            @if (session('resent'))
                <div class="text-green-500 mb-4">
                    {{ $translations[$lang]['verification_sent'] }}
                </div>
            @endif

            <p>{{ $translations[$lang]['check_email'] }}</p>
            <p>{{ $translations[$lang]['not_received'] }},</p>
            <form method="POST" action="{{ route($lang . '.verification.resend') }}">
                @csrf
                <button type="submit" class="text-purple-500 hover:text-purple-600">
                    {{ $translations[$lang]['resend'] }}
                </button>
            </form>
        </div>
    </div>
@endsection
