<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title }} | Vizii E-skola</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
        type="image/x-icon" />
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>

    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: [asset('assets/admin/assets/css/fonts.min.css')],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>
    {{-- <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"> --}}
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/kaiadmin.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/demo.css') }}" />
</head>
@php
    $translations = [
        'en' => [
            'logout' => 'Logout',
            'modules' => 'Modules',
            'topics' => 'Topics',
            'tests' => 'Tests',
            'certificates' => 'Certificates',
            'profile' => 'Profile',
            'dictionaries' => 'Dictionaries',
            'contact' => 'Contact',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms',
            'error_title' => 'Error',
            'cookies' => 'Cookie Policy',
        ],
        'lv' => [
            'logout' => 'Iziet',
            'modules' => 'Moduļi',
            'topics' => 'Tēmas',
            'tests' => 'Testi',
            'certificates' => 'Sertifikāti',
            'profile' => 'Profils',
            'dictionaries' => 'Vārdnīcas',
            'contact' => 'Kontakti',
            'privacy' => 'Privātuma politika',
            'terms' => 'Noteikumi',
            'error_title' => 'Kļūda',
            'cookies' => 'Sīkdatņu politika',
        ],
        'ru' => [
            'logout' => 'Выйти',
            'modules' => 'Модули',
            'topics' => 'Темы',
            'tests' => 'Тесты',
            'certificates' => 'Сертификаты',
            'profile' => 'Профиль',
            'dictionaries' => 'Словари',
            'contact' => 'Контакты',
            'privacy' => 'Политика конфиденциальности',
            'terms' => 'Условия',
            'error_title' => 'Ошибка',
            'cookies' => 'Политика использования файлов cookie',
        ],
        'ua' => [
            'logout' => 'Вийти',
            'modules' => 'Модулі',
            'topics' => 'Теми',
            'tests' => 'Тести',
            'certificates' => 'Сертифікати',
            'profile' => 'Профіль',
            'dictionaries' => 'Словники',
            'contact' => 'Контакти',
            'privacy' => 'Політика конфіденційності',
            'terms' => 'Умови',
            'error_title' => 'Помилка',
            'cookies' => 'Політика щодо файлів cookie',
        ],
    ];
    $lang = Session::get('lang', 'lv');
    $termsUrl = route($lang . '.guest.privacy', ['type' => 'terms']);
    $privacyUrl = route($lang . '.guest.privacy', ['type' => 'privacy']);
    $cookiesUrl = route($lang . '.guest.privacy', ['type' => 'cookies']);
@endphp
<style>
    .logo-app {
        transition-property: all !important;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
        transition-duration: 300ms !important;
        color: white !important;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .logo-app:hover {
        color: #a78bfa !important;
    }

    .main-panel {
        width: 100% !important;
    }

    .main-header {
        width: 100% !important;
    }

    .language-switcher {
        background: rgba(255, 255, 255, 0.1);
        color: black;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        min-width: 40px;
    }

    .language-switcher:hover {
        background: rgba(152, 16, 250, 0.2);
        border-color: #9810fa;
        color: #9810fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(152, 16, 250, 0.2);
    }

    .language-switcher.active {
        background: linear-gradient(135deg, #9810fa, #7c0dd6);
        color: white !important;
        border-color: transparent;
        box-shadow: 0 2px 12px rgba(152, 16, 250, 0.4);
    }

    .language-switcher.active:hover {
        transform: none;
    }

    @media (max-width: 991px) {
        .pc-switcher.navbar-brand {
            display: none !important;
        }

        .language-switcher {
            color: white !important;
        }
    }

    .language-switcher {
        height: 38px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-profile {
        background-color: #9810fa !important;
        color: white !important;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-profile:hover {
        background: rgba(152, 16, 250, 0.2) !important;
        border-color: #9810fa !important;
        color: #9810fa !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(152, 16, 250, 0.2) !important;
    }

    .btn-profile.active {
        background: linear-gradient(135deg, #9810fa, #7c0dd6) !important;
        color: white !important;
        border-color: transparent !important;
        box-shadow: 0 2px 12px rgba(152, 16, 250, 0.4) !important;
    }

    .underline {
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, #9810FA 0%, rgba(13, 110, 253, 0) 70%);
        margin: 1rem 0;
    }
</style>

<body>

    <div class="wrapper">



        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="container-fluid d-flex align-items-center">
                        <a href="/" class="logo d-flex align-items-center gap-2">
                            <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                                alt="Vizii Logo" class="navbar-brand" height="40" />
                            <h3 class="fw-bold mt-3  text-black">Vizii
                                E-Skola</h3>
                        </a>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom px-4">
                    <div class="container-fluid d-flex align-items-center">
                        <a href="/" class="logo d-flex align-items-center">
                            <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                                alt="Vizii Logo" class="navbar-brand" height="40" />
                            <h3 class="fw-bold mt-2 text-black">Vizii
                                E-Skola</h3>
                        </a>
                    </div>
                </nav>
            </div>


            @if (session('success'))
                <script>
                    Swal.fire('Veiksmīgi!', '{{ session('success') }}', 'success');
                </script>
            @endif
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: '{{ $translations[$lang]['error_title'] }}',
                        html: `{!! implode('<br>', $errors->all()) !!}`
                    });
                </script>
            @endif
            @if (session('error'))
                <script>
                    Swal.fire('{{ $translations[$lang]['error_title'] }}', '{{ session('error') }}', 'error');
                </script>
            @endif


            <div class="container">
                <div class="page-inner">
                    <div class="card">
                        @php

                            $lang = Session::get('lang', 'lv');
                        @endphp

                        <div class="container py-5">
                            <div class="text-center my-5">
                                <h1 class="display-4 fw-bold text-primary">{{ $title }}</h1>
                                <div class="underline mx-auto"></div>
                            </div>

                            <div class="row">
                                <div class="card-contact shadow-lg border-0 h-100">
                                    <div class="card-body p-4">
                                        <div class="mb-5">
                                            <h3 class="mb-4">
                                                {!! $terms->{'content_' . $lang} !!}
                                            </h3>
                                            <div class="ps-4">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <style>
                            .underline {
                                width: 100px;
                                height: 3px;
                                background: linear-gradient(90deg, #9810FA 0%, rgba(13, 110, 253, 0) 70%);
                                margin: 1rem 0;
                            }

                            .link-hover {
                                color: #333;
                                transition: all 0.3s ease;
                            }

                            .link-hover:hover {
                                color: #9810FA;
                                transform: translateX(5px);
                            }

                            .card-contact {
                                transition: transform 0.3s ease;
                                border-radius: 15px;
                            }

                            .card-contact:hover {
                                transform: translateY(-5px);
                            }

                            .map-iframe {
                                border: none;
                                border-radius: 10px;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                            }
                        </style>

                    </div>
                </div>
            </div>




            <footer class="footer">
                <div class=" text-center w-full">
                    <a class=" text-primary ms-1 mx-3"
                        href="{{ $termsUrl }}">{{ $translations[$lang]['terms'] }}</a>
                    <a class=" text-primary ms-1 mx-3"
                        href="{{ $privacyUrl }}">{{ $translations[$lang]['privacy'] }}</a>
                    <a class=" text-primary ms-1 mx-3"
                        href="{{ $cookiesUrl }}">{{ $translations[$lang]['cookies'] }}</a>
                </div>
            </footer>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/admin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/admin/assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/admin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/admin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/admin/assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/admin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/admin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/admin/assets/js/kaiadmin.min.js') }}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    {{-- <script src="{{ asset('assets/admin/assets/js/setting-demo.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/admin/assets/js/demo.js') }}"></script> --}}

</body>

</html>
