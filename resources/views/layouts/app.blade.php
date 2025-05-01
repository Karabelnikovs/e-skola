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
        ],
    ];
    $lang = Session::get('lang', 'lv');

    $new_certificates = DB::table('certificates')
        ->where('user_id', auth()->user()->id)
        ->where('is_read', 0)
        ->count();
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
        <!-- Sidebar -->
        {{-- <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <div class="navbar-brand d-flex align-items-center flex-wrap gap-2 justify-content-center">
                        @php
                            $segments = request()->segments();
                            $currentLocale = $segments[0] ?? 'lv';
                            $pathWithoutLocale = implode('/', array_slice($segments, 1));
                            $queryString = request()->getQueryString() ? '?' . request()->getQueryString() : '';
                        @endphp
                        @foreach (config('locale.supported') as $language)
                            <a @if (!empty($pathWithoutLocale)) { href="/{{ $language }}/{{ $pathWithoutLocale }}{{ $queryString }}" } @else { href="/{{ $language }}" } @endif
                                @if ($currentLocale == $language) style="font-weight: bold; color: #9810fa !important;" @else style="color: white;" @endif
                                class="logo-app logo">
                                {{ strtoupper($language) }}
                            </a>
                        @endforeach
                    </div>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item {{ Route::currentRouteName() == $lang . '.module.show' ? 'active' : '' }}">
                            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed"
                                aria-expanded="{{ Route::currentRouteName() == $lang . '.module.show' ? 'true' : 'false' }}">
                                <i class="fas fa-book-open"></i>
                                <p>{{ $translations[$lang]['modules'] }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse {{ Route::currentRouteName() == $lang . '.module.show' ? 'show' : '' }}"
                                id="dashboard">
                                <ul class="nav nav-collapse">
                                    @foreach ($courses as $course)
                                        <li
                                            class="{{ Route::currentRouteName() == $lang . '.module.show' && request()->route()->parameter('id') == $course->id ? 'active' : '' }}">
                                            <a href="{{ route($lang . '.module.show', $course->id) }}">
                                                <span class="sub-item">{{ $course->{'title_' . $lang} }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == $lang . '.tests' ? 'active' : '' }}">
                            <a href="{{ route($lang . '.tests') }}">
                                <i class="fas fa-pen-square"></i>
                                <p>{{ $translations[$lang]['tests'] }}</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == $lang . '.topics.view' ? 'active' : '' }}">
                            <a href="{{ route($lang . '.topics.view') }}">
                                <i class="fas fa-file"></i>
                                <p>{{ $translations[$lang]['topics'] }}</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Route::currentRouteName() == $lang . '.dictionaries.view' ? 'active' : '' }}">
                            <a href="{{ route($lang . '.dictionaries.view') }}">
                                <i class="fas fa-book"></i>
                                <p>{{ $translations[$lang]['dictionaries'] }}</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Route::currentRouteName() == $lang . '.certificates' ? 'active' : '' }}">
                            <a href="{{ route($lang . '.certificates') }}">
                                <i class="fa-solid fa-circle-check"></i>
                                <p>{{ $translations[$lang]['certificates'] }}</p>
                                @if ($new_certificates > 0)
                                    <span class="badge badge-success">{{ $new_certificates }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == $lang . '.profile' ? 'active' : '' }}">
                            <a href="{{ route($lang . '.profile') }}">
                                <i class="fa-solid fa-user-alt"></i>
                                <p>{{ $translations[$lang]['profile'] }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
        <!-- End Sidebar -->


        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <div class="navbar-brand d-flex align-items-center gap-3 justify-content-center mx-3  py-3">
                            @php
                                $segments = request()->segments();
                                $currentLocale = $segments[0] ?? 'lv';
                                $pathWithoutLocale = implode('/', array_slice($segments, 1));
                                $queryString = request()->getQueryString() ? '?' . request()->getQueryString() : '';
                            @endphp

                            @foreach (config('locale.supported') as $language)
                                <a href="{{ url("/$language/$pathWithoutLocale$queryString") }}"
                                    class="language-switcher d-flex align-items-center justify-content-center text-decoration-none 
                  rounded-pill px-2 transition-all {{ $currentLocale == $language ? 'active' : '' }}">
                                    <span class="fw-semibold">{{ strtoupper($language) }}</span>
                                </a>
                            @endforeach
                        </div>
                        {{-- <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div> --}}
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom px-4">
                    <div class="container-fluid">
                        <a href="/" class="logo d-flex align-items-center">
                            <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                                alt="Vizii Logo" class="navbar-brand" height="40" />
                            <h3 class="fw-bold mt-2 text-black">Vizii
                                E-Skola</h3>
                        </a>
                        <div class="navbar-brand d-flex align-items-center gap-3 justify-content-center pc-switcher">
                            @php
                                $segments = request()->segments();
                                $currentLocale = $segments[0] ?? 'lv';
                                $pathWithoutLocale = implode('/', array_slice($segments, 1));
                                $queryString = request()->getQueryString() ? '?' . request()->getQueryString() : '';
                            @endphp

                            @foreach (config('locale.supported') as $language)
                                <a href="{{ url("/$language/$pathWithoutLocale$queryString") }}"
                                    class="language-switcher d-flex align-items-center justify-content-center text-decoration-none rounded-pill px-2 transition-all {{ $currentLocale == $language ? 'active' : '' }}">
                                    <span class="fw-semibold">{{ strtoupper($language) }}</span>
                                </a>
                            @endforeach
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route($lang . '.profile') }}" class="btn btn-round btn-profile">
                                <i class="fa-solid fa-user-alt"></i>
                            </a>
                            <form method="POST" action="{{ route($lang . '.logout') }}">
                                @csrf

                                <button type="submit" value=""
                                    class="btn btn-round btn-danger">{{ $translations[$lang]['logout'] }} <i
                                        class="fas fa-caret-right"></i> </button>
                            </form>
                        </div>
                    </div>
                </nav>
                <!-- End Navbar -->
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
                        title: 'Kļūda!',
                        html: `{!! implode('<br>', $errors->all()) !!}`
                    });
                </script>
            @endif
            @if (session('error'))
                <script>
                    Swal.fire('Kļūda!', '{{ session('error') }}', 'error');
                </script>
            @endif


            <div class="container">
                <div class="page-inner">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </div>




            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class=" text-primary ms-1"
                                    href="{{ route('terms.show') }}">{{ $translations[$lang]['terms'] }}</a>
                            </li>
                            <li class="nav-item">
                                <a class=" text-primary ms-1"
                                    href="{{ route('privacy.show') }}">{{ $translations[$lang]['privacy'] }}</a>
                            </li>
                        </ul>
                    </nav>

                    <div class="copyright d-flex justify-content-center align-items-center"><span>
                            {{ now()->year }}, izstrādātājs </span><a target="_blank" class=" text-primary ms-1"
                            href="http://www.devera.lv">Devera.lv</a>
                    </div>
                    <div>
                        <a class=" text-primary ms-1"
                            href="{{ route('contacts.show') }}">{{ $translations[$lang]['contact'] }}</a>
                    </div>
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
