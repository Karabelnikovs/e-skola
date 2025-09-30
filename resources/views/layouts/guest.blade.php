<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Reset Password' }}</title>


    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('assets/notika/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/notika/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/notika/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/wave/waves.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/notika-custom-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/notika/css/responsive.css') }}">
    <script src="{{ asset('assets/notika/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div
        class="relative flex min-h-screen text-gray-800 antialiased flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12 items-center px-6">
        <div class="relative py-3 sm:w-96 xl:w-1/2 w-full text-center">
            @php
                $segments = request()->segments();
                $currentLocale = $segments[0] ?? 'lv';
                $pathWithoutLocale = implode('/', array_slice($segments, 1));
                $queryString = request()->getQueryString() ? '?' . request()->getQueryString() : '';
            @endphp

            @foreach (config('locale.supported') as $lang)
                <a href="/{{ $lang }}/{{ $pathWithoutLocale }}{{ $queryString }}"
                    @if ($currentLocale == $lang) style="font-weight: bold;" @endif>
                    {{ strtoupper($lang) }}
                </a>
            @endforeach
            @yield('content')
        </div>
    </div>
</body>

</html>
