<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Oops...</title>
    <link rel="icon" href="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
        sizes="32x32">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@php
    $translations = [
        'en' => [
            'reason1' => "You're not logged in...",
            'reason2' => 'Only for admins...',
            'error' => 'Error',
            'sorry' => 'Sorry about that! Please visit our homepage to get where you need to go.',
            'button' => 'Back',
            'in_development' => 'In development',
        ],
        'lv' => [
            'reason1' => 'Jūs neesat pieteicies...',
            'reason2' => 'Tikai administratoriem...',
            'error' => 'Kļūda',
            'sorry' => 'Atvainojiet par to! Lūdzu, apmeklējiet mūsu sākumlapu, lai nokļūtu tur, kur jums jābūt.',
            'button' => 'Atpakaļ',
            'in_development' => 'Izstrādē',
        ],
        'ru' => [
            'reason1' => 'Вы не вошли в систему...',
            'reason2' => 'Только для администраторов...',
            'error' => 'Ошибка',
            'sorry' =>
                'Извините за это! Пожалуйста, посетите нашу главную страницу, чтобы добраться туда, где вам нужно быть.',
            'button' => 'Назад',
            'in_development' => 'В разработке',
        ],
        'ua' => [
            'reason1' => 'Ви не увійшли в систему...',
            'reason2' => 'Тільки для адміністраторів...',
            'error' => 'Помилка',
            'sorry' =>
                'Вибачте за це! Будь ласка, відвідайте нашу домашню сторінку, щоб потрапити туди, де вам потрібно бути.',
            'button' => 'Назад',
            'in_development' => 'В розробці',
        ],
    ];
    $lang = Session::get('lang', 'lv');
@endphp

<body>
    <div
        class="lg:px-24 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16">
        <div class="xl:pt-24 w-full xl:w-1/2 relative pb-12 lg:pb-0">
            <div class="relative">
                <div class="absolute">
                    <div class="">
                        <h1 class="my-2 text-gray-800 font-bold text-2xl">
                            Izstrādē
                        </h1>
                        <p class="my-4 mb-6 text-gray-800"> Šī lapa vēl nav izveidota...
                        </p>
                        <a href="{{ url()->previous() }}"
                            class="sm:w-full lg:w-auto my-2 border rounded md py-4 px-8 text-center bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-opacity-50">{{ $translations[$lang]['button'] }}</a>
                    </div>
                </div>
                <div>
                    <img src="https://i.ibb.co/G9DC8S0/404-2.png" />
                </div>
            </div>
        </div>
        <div>
            <img src="https://i.ibb.co/ck1SGFJ/Group.png" />
        </div>
    </div>

</body>

</html>
