<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CURHATIN Admin')</title>
    <meta name="description" content="@yield('meta_description', 'Panel Admin CURHATIN - Sistem E-Mental Health Kampus')">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <!-- Material Icons Round -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Global Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/admin/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/base.css') }}">

    @hasSection('content')
        <link rel="stylesheet" href="{{ asset('css/admin/layout.css') }}">
    @endif

    @stack('styles')
</head>
<body>

    @hasSection('body')
        @yield('body')
    @else
        <!-- Full-Width Top Header (Sesuai Desain) -->
        <header class="app-header" role="banner">
            <div class="app-header__brand">
                <a href="{{ route('admin.dashboard') }}" class="app-header__logo-link">
                    <img src="{{ asset('images/curhatin-logo.png') }}" alt="Logo CURHATIN" class="app-header__logo-img">
                    <span class="app-header__brand-text">CURHATIN</span>
                </a>
            </div>

            <div class="app-header__actions">
                <button class="topbar-hamburger" id="hamburgerBtn" aria-label="Toggle menu">
                    <span class="material-icons-round">menu</span>
                </button>
            </div>
        </header>

        <div class="admin-shell">

            <!-- Sidebar Kiri (Krem #F3E6D3) -->
            <aside class="admin-sidebar" id="adminSidebar">
                <nav class="sidebar-nav" role="navigation" aria-label="Menu Admin">
                    @yield('sidebar-nav')
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-footer__logout">
                            <span class="material-icons-round">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Overlay Mobile -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- Konten Utama (Putih #FFFFFF) -->
            <div class="admin-content">
                <main class="page-content">
                    @yield('content')
                </main>
            </div>

        </div>

        <script src="{{ asset('js/admin/sidebar.js') }}"></script>
    @endif

    @stack('scripts')
</body>
</html>
