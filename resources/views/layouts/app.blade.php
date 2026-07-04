<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'NAVIER Counter System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de gestión de contadores Ricoh" name="description" />
    <meta content="NAVIER" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .logo-text {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .logo-text-dark {
            color: #313a46;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="{{ route('dashboard') }}" class="logo-light">
                            <span class="logo-lg logo-text">
                                NAVIER
                            </span>
                            <span class="logo-sm logo-text">
                                N
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="{{ route('dashboard') }}" class="logo-dark">
                            <span class="logo-lg logo-text-dark">
                                NAVIER
                            </span>
                            <span class="logo-sm logo-text-dark">
                                N
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="ri-menu-2-fill"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode" style="cursor: pointer;">
                            <i class="ri-moon-line fs-22"></i>
                        </div>
                    </li>
                    <li class="d-none d-md-inline-block">
                        <a class="nav-link" href="#" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line fs-22"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" width="32" class="rounded-circle">
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">@auth {{ auth()->user()->name }} @else Admin @endauth</h5>
                                <h6 class="my-0 fw-normal">Administrator</h6>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bienvenido !</h6>
                            </div>

                            @auth
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                            @endauth
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="{{ route('dashboard') }}" class="logo logo-light">
                <span class="logo-lg logo-text">
                    NAVIER
                </span>
                <span class="logo-sm logo-text">
                    N
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                <span class="logo-lg logo-text-dark">
                    NAVIER
                </span>
                <span class="logo-sm logo-text-dark">
                    N
                </span>
            </a>

            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                
                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">Principal</li>

                    <li class="side-nav-item">
                        <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ri-dashboard-2-fill"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('equipos.index') }}" class="side-nav-link {{ request()->routeIs('equipos.*') ? 'active' : '' }}">
                            <i class="ri-printer-fill"></i>
                            <span> Equipos </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('clientes.index') }}" class="side-nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                            <i class="ri-building-fill"></i>
                            <span> Clientes </span>
                        </a>
                    </li>

                    <li class="side-nav-title mt-3">Contadores</li>

                    <li class="side-nav-item">
                        <a href="{{ route('lecturas.index') }}" class="side-nav-link {{ request()->routeIs('lecturas.*') ? 'active' : '' }}">
                            <i class="ri-bar-chart-2-fill"></i>
                            <span> Lecturas </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('alertas.index') }}" class="side-nav-link {{ request()->routeIs('alertas.*') ? 'active' : '' }}">
                            <i class="ri-error-warning-fill"></i>
                            <span> Alertas </span>
                            @if(isset($alertasCount) && $alertasCount > 0)
                                <span class="badge bg-danger rounded-pill float-end">{{ $alertasCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="side-nav-title mt-3">Sistema</li>

                    <li class="side-nav-item">
                        <a href="{{ route('agentes.index') }}" class="side-nav-link {{ request()->routeIs('agentes.*') ? 'active' : '' }}">
                            <i class="ri-download-cloud-2-fill"></i>
                            <span> Agentes </span>
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->is_super_admin)
                    <li class="side-nav-item mt-3">
                        <a href="{{ route('tenants.index') }}" class="side-nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
                            <i class="ri-admin-fill text-warning"></i>
                            <span class="text-warning"> Empresas (SA) </span>
                        </a>
                    </li>
                    @endif

                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">                                    
                                <h4 class="page-title">@yield('page-title', 'NAVIER')</h4>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible text-bg-success border-0 fade show" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Éxito - </strong> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible text-bg-danger border-0 fade show" role="alert">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            <strong>Error - </strong> {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © NAVIER - Sistema de contadores
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
