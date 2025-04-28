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
        ],
        'lv' => [
            'logout' => 'Iziet',
            'modules' => 'Moduļi',
            'topics' => 'Tēmas',
            'tests' => 'Testi',
            'certificates' => 'Sertifikāti',
            'profile' => 'Profils',
            'dictionaries' => 'Vārdnīcas',
        ],
        'ru' => [
            'logout' => 'Выйти',
            'modules' => 'Модули',
            'topics' => 'Темы',
            'tests' => 'Тесты',
            'certificates' => 'Сертификаты',
            'profile' => 'Профиль',
            'dictionaries' => 'Словари',
        ],
        'ua' => [
            'logout' => 'Вийти',
            'modules' => 'Модулі',
            'topics' => 'Теми',
            'tests' => 'Тести',
            'certificates' => 'Сертифікати',
            'profile' => 'Профіль',
            'dictionaries' => 'Словники',
        ],
    ];
    $lang = Session::get('lang', 'lv');
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
</style>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    {{-- <a href="/admin" class="logo">
                        <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                            alt="Vizii Logo" class="navbar-brand" height="20" /> <span class="text-white mx-2">Vizii
                            E-Skola</span>
                    </a> --}}
                    {{-- <div class="navbar-brand d-flex align-items-center"> --}}
                    <div class="navbar-brand d-flex align-items-center flex-wrap gap-2 justify-content-center">

                        @php
                            $segments = request()->segments();
                            $currentLocale = $segments[0] ?? 'lv';
                            $pathWithoutLocale = implode('/', array_slice($segments, 1));
                            $queryString = request()->getQueryString() ? '?' . request()->getQueryString() : '';
                        @endphp

                        @foreach (config('locale.supported') as $language)
                            <a @if (!empty($pathWithoutLocale)) { href="/{{ $language }}/{{ $pathWithoutLocale }}{{ $queryString }}" } @else { href="/{{ $language }}" } @endif
                                @if ($currentLocale == $language) style="font-weight: bold; color: #9810fa !important; " @else style="color: white;" @endif
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
                        {{-- <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">{{ $translations[$lang]['modules'] }}</h4>
                        </li> --}}
                        <li class="nav-item active">
                            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                                <i class="fas fa-book-open"></i>
                                <p>{{ $translations[$lang]['modules'] }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="dashboard">
                                <ul class="nav nav-collapse">

                                    @foreach ($courses as $course)
                                        <li>
                                            <a href="{{ route('module.show', $course->id) }}">

                                                <span class="sub-item">{{ $course->{'title_' . $lang} }}</span>

                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('development') }}">
                                <i class="fas fa-file"></i>
                                <p>{{ $translations[$lang]['topics'] }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('development') }}">
                                <i class="fas fa-pen-square"></i>
                                <p>{{ $translations[$lang]['tests'] }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('development') }}">
                                <i class="fas fa-book"></i>
                                <p>{{ $translations[$lang]['dictionaries'] }}</p>
                            </a>
                        <li class="nav-item">
                            <a href="{{ route('development') }}">
                                <i class="fa-solid fa-circle-check"></i>
                                <p>{{ $translations[$lang]['certificates'] }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('development') }}">
                                <i class="fa-solid fa-user-alt"></i>
                                <p>{{ $translations[$lang]['profile'] }}</p>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="{{ asset('assets/admin/assets/img/kaiadmin/logo_light.svg') }}"
                                alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
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
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <a href="/" class="logo d-flex align-items-center">
                            <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                                alt="Vizii Logo" class="navbar-brand" height="40" />
                            <h3 class="fw-bold mt-2 text-black">Vizii
                                E-Skola</h3>
                        </a>
                        <div class="">
                            <form method="POST" action="{{ route('logout') }}">
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
                <div class=" w-full flex justify-center align-center">

                    <div class="copyright d-flex justify-content-center align-items-center"><span>
                            {{ now()->year }}, izstrādātājs </span><a target="_blank"
                            class="nav-link text-primary ms-1" href="http://www.devera.lv">Devera.lv</a>
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
