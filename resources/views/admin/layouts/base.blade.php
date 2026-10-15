<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CURHATIN Admin')</title>
    <meta name="description" content="@yield('meta_description', 'Panel Admin CURHATIN - Sistem E-Mental Health')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        /* ======================================
           CURHATIN Design System
           ====================================== */
        :root {
            --maroon-900: #3B0A0A;
            --maroon-800: #5C1010;
            --maroon-700: #6B1515;
            --maroon-600: #7A1A1A;
            --maroon-500: #8B1E1E;
            --maroon-400: #A03030;
            --maroon-300: #C05050;
            --maroon-200: #D88080;
            --maroon-100: #EEB0B0;
            --maroon-50:  #FBE8E8;

            --cream-50:  #FFFDF9;
            --cream-100: #FFF8F0;
            --cream-200: #FFF0E0;
            --cream-300: #FFEBD5;
            --cream-400: #FFE0C0;

            --neutral-50:  #FAFAFA;
            --neutral-100: #F5F5F5;
            --neutral-200: #E5E5E5;
            --neutral-300: #D4D4D4;
            --neutral-400: #A3A3A3;
            --neutral-500: #737373;
            --neutral-600: #525252;
            --neutral-700: #404040;
            --neutral-800: #262626;
            --neutral-900: #171717;

            --success: #22C55E;
            --success-bg: #F0FDF4;
            --warning: #F59E0B;
            --warning-bg: #FFFBEB;
            --danger:  #EF4444;
            --danger-bg: #FEF2F2;
            --info:    #3B82F6;

            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

            --sidebar-width: 250px;
            --navbar-height: 60px;

            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            color: var(--neutral-800);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: var(--cream-100);
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        /* ======================================
           Dashboard Layout (Sidebar + Content)
           ====================================== */
        .admin-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ---- Sidebar ---- */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--cream-100);
            border-right: 1px solid var(--neutral-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 200;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: var(--maroon-800);
            min-height: var(--navbar-height);
        }

        .sidebar-brand__icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--maroon-300), var(--maroon-500));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(139, 30, 30, 0.4);
        }

        .sidebar-brand__icon .material-icons-round {
            font-size: 20px;
            color: white;
        }

        .sidebar-brand__text {
            font-size: 18px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-nav__item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-600);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sidebar-nav__item:hover {
            background: var(--cream-200);
            color: var(--maroon-700);
        }

        .sidebar-nav__item.active {
            background: var(--maroon-700);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(139, 30, 30, 0.25);
        }

        .sidebar-nav__item .material-icons-round {
            font-size: 20px;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--neutral-200);
        }

        .sidebar-footer__logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--neutral-200);
            background: transparent;
            font-family: var(--font-family);
            font-size: 13px;
            font-weight: 500;
            color: var(--neutral-600);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-footer__logout:hover {
            background: var(--danger-bg);
            border-color: #FECACA;
            color: var(--danger);
        }

        .sidebar-footer__logout .material-icons-round {
            font-size: 18px;
        }

        /* ---- Main Content Area ---- */
        .admin-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- Top Navbar ---- */
        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: var(--navbar-height);
            background: var(--maroon-800);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
        }

        .topbar-hamburger .material-icons-round {
            font-size: 24px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.9);
        }

        .topbar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--maroon-300), var(--maroon-600));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .topbar-user__name {
            font-size: 13px;
            font-weight: 600;
            color: white;
        }

        .topbar-user__role {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
        }

        /* ---- Page Content ---- */
        .page-content {
            flex: 1;
            padding: 32px;
        }

        /* ---- Mobile Overlay ---- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 190;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ---- Responsive ---- */
        @media (max-width: 900px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .topbar-hamburger {
                display: flex;
            }

            .page-content {
                padding: 20px 16px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    @hasSection('body')
        {{-- Raw body for pages like login that don't use dashboard shell --}}
        @yield('body')
    @else
        <div class="admin-shell">

            {{-- Sidebar --}}
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

            {{-- Mobile Overlay --}}
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            {{-- Main --}}
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

        <script>
            // Mobile sidebar toggle
            const sidebar   = document.getElementById('adminSidebar');
            const overlay   = document.getElementById('sidebarOverlay');
            const hamburger = document.getElementById('hamburgerBtn');

            if (hamburger) {
                hamburger.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('show');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                });
            }
        </script>
    @endif

    @stack('scripts')
</body>
</html>
