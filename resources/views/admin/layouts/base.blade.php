<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CURHATIN Admin')</title>
    <meta name="description" content="@yield('meta_description', 'Panel Admin CURHATIN - Sistem E-Mental Health')">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    
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
        <div class="admin-shell">

            
            <aside class="admin-sidebar" id="adminSidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-brand__icon">
                        <span class="material-icons-round">favorite</span>
                    </div>
                    <span class="sidebar-brand__text">CURHATIN</span>
                </div>

                <nav class="sidebar-nav" role="navigation" aria-label="Menu Admin">
                    @yield('sidebar-nav')
                </nav>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-footer__logout">
                            <span class="material-icons-round">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            
            <div class="admin-content">
                <header class="admin-topbar">
                    <div class="topbar-left">
                        <button class="topbar-hamburger" id="hamburgerBtn" aria-label="Toggle menu">
                            <span class="material-icons-round">menu</span>
                        </button>
                    </div>
                    <div class="topbar-right">
                        <div class="topbar-user">
                            <div class="topbar-avatar">
                                {{ strtoupper(substr(session('admin_nama', 'A'), 0, 1)) }}
                            </div>
                            <div>
                                <div class="topbar-user__name">{{ session('admin_nama', 'Admin') }}</div>
                                <div class="topbar-user__role">Administrator</div>
                            </div>
                        </div>
                    </div>
                </header>

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
