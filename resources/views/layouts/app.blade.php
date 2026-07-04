<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NAVIER Counter System - Sistema de gestión de contadores Ricoh">
    <title>@yield('title', 'NAVIER Counter System')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a1d23;
            --sidebar-hover: #2a2d35;
            --sidebar-active: #3b82f6;
            --body-bg: #f0f2f5;
            --card-bg: #ffffff;
            --text-primary: #1a1d23;
            --text-secondary: #6b7280;
            --accent: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        [data-theme="dark"] {
            --body-bg: #1e222d;
            --card-bg: #272c39;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --sidebar-bg: #1a1d23;
            --sidebar-hover: #222631;
        }

        [data-theme="dark"] body {
            color: var(--text-primary);
        }

        [data-theme="dark"] .table {
            color: var(--text-primary);
        }

        [data-theme="dark"] .table-card .card-header,
        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .top-bar {
            border-color: #353b49;
        }

        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td {
            border-color: #353b49;
        }

        [data-theme="dark"] .btn-light {
            background-color: #353b49;
            border-color: #353b49;
            color: #e5e7eb;
        }

        [data-theme="dark"] .btn-light:hover {
            background-color: #42495b;
            border-color: #42495b;
            color: #fff;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            min-height: 100vh;
        }

        /* ═══ SIDEBAR ═══ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .sidebar-brand small {
            color: #9ca3af;
            font-size: 0.75rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-label {
            padding: 0.5rem 1.25rem;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #6b7280;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1.25rem;
            color: #d1d5db;
            font-size: 0.875rem;
            font-weight: 400;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(59, 130, 246, 0.12);
            color: var(--sidebar-active);
            border-left-color: var(--sidebar-active);
            font-weight: 500;
        }

        .sidebar-nav .nav-link i {
            width: 22px;
            margin-right: 0.75rem;
            font-size: 1.05rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: var(--sidebar-bg);
        }

        /* ═══ MAIN CONTENT ═══ */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-bar {
            background: var(--card-bg);
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-content {
            padding: 1.5rem;
        }

        /* ═══ CARDS ═══ */
        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e5e7eb;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* ═══ TABLES ═══ */
        .table-card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .table-card .card-header {
            background: transparent;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.25rem;
        }

        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 1px solid #e5e7eb;
        }

        .table td {
            font-size: 0.875rem;
            vertical-align: middle;
        }

        /* ═══ BADGES ═══ */
        .badge-status {
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-online {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .badge-offline {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
        }

        /* ═══ TONER BARS ═══ */
        .toner-bar {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
        }

        .toner-bar .toner-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .toner-black .toner-fill { background: #374151; }
        .toner-cyan .toner-fill { background: #06b6d4; }
        .toner-magenta .toner-fill { background: #ec4899; }
        .toner-yellow .toner-fill { background: #eab308; }

        /* ═══ ALERTS ═══ */
        .alert-item {
            padding: 0.75rem 1rem;
            border-left: 3px solid;
            border-radius: 0 8px 8px 0;
            margin-bottom: 0.5rem;
            background: var(--card-bg);
        }

        .alert-item.alert-toner { border-color: var(--warning); }
        .alert-item.alert-offline { border-color: var(--danger); }
        .alert-item.alert-info { border-color: var(--accent); }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        /* ═══ MISC ═══ */
        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h5><i class="bi bi-printer"></i> NAVIER</h5>
            <small>Counter System v1.0</small>
        </div>

        <div class="sidebar-nav">
            <div class="nav-label">Principal</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('equipos.index') }}" class="nav-link {{ request()->routeIs('equipos.*') ? 'active' : '' }}">
                <i class="bi bi-printer-fill"></i> Equipos
            </a>
            <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Clientes
            </a>

            <div class="nav-label">Contadores</div>
            <a href="{{ route('lecturas.index') }}" class="nav-link {{ request()->routeIs('lecturas.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Lecturas
            </a>
            <a href="{{ route('alertas.index') }}" class="nav-link {{ request()->routeIs('alertas.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Alertas
                @if(isset($alertasCount) && $alertasCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $alertasCount }}</span>
                @endif
            </a>

            <div class="nav-label">Sistema</div>
            <a href="{{ route('agentes.index') }}" class="nav-link {{ request()->routeIs('agentes.*') ? 'active' : '' }}">
                <i class="bi bi-download"></i> Agentes
            </a>
            <a href="{{ route('licencia.index') }}" class="nav-link {{ request()->routeIs('licencia.*') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i> Licencia
            </a>
        </div>

        <div class="sidebar-footer">
            <small class="text-muted">
                <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem;"></i>
                Sistema activo
            </small>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title ms-2">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Dark Mode Toggle -->
                <button class="btn btn-sm btn-light" id="theme-toggle" title="Alternar tema">
                    <i class="bi bi-sun"></i>
                </button>
                <!-- Fullscreen Toggle -->
                <button class="btn btn-sm btn-light" id="fullscreen-toggle" title="Pantalla completa">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-clock"></i> {{ now()->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- PowerX UI Features -->
    <script>
        // Fullscreen Toggle
        document.getElementById('fullscreen-toggle').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable fullscreen: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        });

        // Theme Toggle (Light/Dark)
        const themeBtn = document.getElementById('theme-toggle');
        const themeIcon = themeBtn.querySelector('i');
        const htmlElement = document.documentElement;

        // Load saved theme
        const savedTheme = localStorage.getItem('navier_theme') || 'light';
        if (savedTheme === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
            themeIcon.classList.replace('bi-sun', 'bi-moon');
        }

        themeBtn.addEventListener('click', function() {
            if (htmlElement.getAttribute('data-theme') === 'dark') {
                htmlElement.removeAttribute('data-theme');
                localStorage.setItem('navier_theme', 'light');
                themeIcon.classList.replace('bi-moon', 'bi-sun');
            } else {
                htmlElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('navier_theme', 'dark');
                themeIcon.classList.replace('bi-sun', 'bi-moon');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
