<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <title>Iniciar Sesión | NAVIER Counter System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="NAVIER Counter System" name="description" />
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
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .logo-text-dark {
            color: #313a46;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-6">
                    <div class="position-relative rounded-3 overflow-hidden shadow-lg">
                        <div class="card bg-transparent mb-0">
                            <!-- Logo-->
                            <div class="auth-brand text-center mt-4">
                                <a href="#" class="logo-light">
                                    <span class="logo-text"><i class="ri-printer-fill me-2"></i> NAVIER</span>
                                </a>
                                <a href="#" class="logo-dark">
                                    <span class="logo-text-dark"><i class="ri-printer-fill me-2"></i> NAVIER</span>
                                </a>
                            </div>

                            <div class="card-body p-4">
                                <div class="w-100 text-center mb-4">
                                    <h4 class="pb-0 fw-bold">Iniciar Sesión</h4>
                                    <p class="fw-semibold">Ingresa tu correo y contraseña para acceder al panel.</p>
                                </div>

                                @if($errors->any())
                                    <div class="alert alert-danger bg-danger text-white border-0">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input class="form-control" type="email" name="email" id="email" required="" placeholder="Ej: admin@navier.com" value="{{ old('email') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">Mantener sesión iniciada</label>
                                        </div>
                                    </div>

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-primary w-100 fw-bold" type="submit"> INGRESAR AL SISTEMA </button>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div>

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="bg-body"><script>document.write(new Date().getFullYear())</script> © NAVIER - Sistema de Monitoreo</span>
    </footer>
    
    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>
</html>
