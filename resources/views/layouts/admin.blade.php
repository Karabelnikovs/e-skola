<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title }} | Admin - Vizii E-skola</title>
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
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/admin/assets/css/demo.css') }}" />
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="/admin" class="logo">
                        <img src="https://vizii.lv/urban/wp-content/uploads/sites/2/2021/09/cropped-vizii_fav-32x32.png"
                            alt="Vizii Logo" class="navbar-brand" height="20" /> <span class="text-white mx-2">Vizii
                            E-Skola</span>
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
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Moduļi</h4>
                        </li>
                        <li
                            class="nav-item {{ Route::currentRouteName() == 'module.all' ? 'active' : '' }} {{ Route::currentRouteName() == 'module' ? 'active' : '' }}">
                            <a data-bs-toggle="collapse" href="#dashboard" class="collapsed"
                                aria-expanded="{{ Route::currentRouteName() == 'module' ? 'true' : 'false' }}">
                                <i class="fas fa-book-open"></i>
                                <p>Moduļi</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse {{ Route::currentRouteName() == 'module.all' ? 'show' : '' }} {{ Route::currentRouteName() == 'module' ? 'show' : '' }}"
                                id="dashboard">
                                <ul class="nav nav-collapse">
                                    <li class=" {{ Route::currentRouteName() == 'module.all' ? 'active' : '' }}">
                                        <a href="{{ route('module.all') }}">
                                            <span class="sub-item">Visi moduļi </span>
                                        </a>
                                    </li>
                                    @foreach ($courses as $course)
                                        <li
                                            class="{{ Route::currentRouteName() == 'module' && request()->route()->parameter('id') == $course->id ? 'active' : '' }}">
                                            <a href="{{ route('module', $course->id) }}">
                                                <span class="sub-item">{{ $course->title_lv }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'module.create' ? 'active' : '' }}">
                            <a href="{{ route('module.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                <p>Izveidot jaunu moduli</p>
                            </a>
                        </li>

                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Sadaļas</h4>
                        </li>


                        <li class="nav-item {{ Route::currentRouteName() == 'admin.users' ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}">
                                <i class="fas fa-users"></i>
                                <p>Lietotāji</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'admin.import.users' ? 'active' : '' }}">
                            <a href="{{ route('admin.import.users') }}">
                                <i class="fas fa-users-cog"></i>
                                <p>Lietotāju Imports</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'tests.users' ? 'active' : '' }}">
                            <a href="{{ route('tests.users') }}">
                                <i class="fas fa-pen-square"></i>
                                <p>Testi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'topics' ? 'active' : '' }}">
                            <a href="{{ route('topics') }}">
                                <i class="fas fa-file"></i>
                                <p>Tēmas</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Route::currentRouteName() == 'certificates' ? 'active' : '' }} {{ Route::currentRouteName() == 'user.certificates' ? 'active' : '' }}">
                            <a href="{{ route('certificates') }}">
                                <i class="fa-solid fa-circle-check"></i>
                                <p>Sertifikāti</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'contacts' ? 'active' : '' }}">
                            <a href="{{ route('contacts') }}">
                                <i class="fas fa-envelope"></i>
                                <p>Kontaktsekcija</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'rules' ? 'active' : '' }}">
                            <a href="{{ route('rules') }}">
                                <i class="fas fa-info-circle"></i>
                                <p>Noteikumi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::currentRouteName() == 'privacy' ? 'active' : '' }}">
                            <a href="{{ route('privacy') }}">
                                <i class="fas fa-lock"></i>
                                <p>Privātuma politika</p>
                            </a>
                        </li>

                        <li class="nav-item {{ Route::currentRouteName() == 'users-progress' ? 'active' : '' }}">
                            <a href="{{ route('users-progress') }}">
                                <i class="fas fa-list-ul"></i>
                                <p>Lietotāju progress</p>
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


                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">




                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
            <div class="container">
                <div class="page-inner">
                    <div class="card">
                        @yield('content')

                    </div>
                </div>
            </div>


        </div>


    </div>
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
