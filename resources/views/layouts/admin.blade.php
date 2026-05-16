<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    @vite(['resources/css/app.css'])
    @include('partials.site-theme-css')
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            font-family: var(--font-geist-sans), Arial, sans-serif;
            margin: 0;
            background: var(--admin-page-bg);
            color: #1f2937;
            overflow-x: hidden;
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .body-grid {
            display: grid;
            grid-template-columns: 240px 1fr;
            flex: 1;
            min-height: 0;
        }

        .body-grid>* {
            min-width: 0;
        }

        aside {
            background: var(--brand-navy);
            color: var(--brand-topbar-muted);
            padding: 20px;
            position: relative;
        }

        aside nav {
            display: grid;
            gap: 10px;
        }

        .nav-group {
            border: 1px solid color-mix(in srgb, var(--brand-navy-mid) 55%, white);
            border-radius: 8px;
            background: color-mix(in srgb, var(--brand-navy-mid) 35%, transparent);
            overflow: hidden;
        }

        .nav-group summary {
            list-style: none;
            cursor: pointer;
            user-select: none;
            padding: 8px 9px;
            font-size: 13px;
            font-weight: 700;
            color: var(--brand-topbar-muted);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .nav-group summary::-webkit-details-marker {
            display: none;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            padding: 6px 8px 10px;
        }

        .nav-sub a {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-weight: 600;
            padding: 7px 9px;
        }

        .nav-sub a.parent-page {
            font-weight: 800;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-close {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            width: 32px;
            height: 32px;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        aside a {
            color: var(--brand-topbar-muted);
            text-decoration: none;
            display: block;
            padding: 8px 9px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            background: color-mix(in srgb, white 8%, transparent);
            border: 1px solid color-mix(in srgb, var(--brand-topbar-muted) 28%, transparent);
        }

        aside a:hover {
            background: color-mix(in srgb, var(--brand-accent) 32%, transparent);
            border-color: color-mix(in srgb, var(--brand-accent-hover) 45%, white);
            color: #fff;
        }

        aside a.active {
            background: var(--brand-accent);
            border-color: var(--brand-accent-hover);
            color: #fff;
            font-weight: 600;
        }

        main {
            padding: 24px;
            min-width: 0;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border-bottom: 1px solid color-mix(in srgb, var(--brand-navy-mid) 22%, #e5e7eb);
            padding: 3px 18px;
            position: sticky;
            top: 0;
            z-index: 60;
            position: relative;
        }

        .logout-form {
            margin-left: auto;
        }

        .top-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-header-title {
            display: inline-flex;
            align-items: center;
            line-height: 0;
            position: static;
            transform: none;
            white-space: nowrap;
            text-decoration: none;
        }

        .top-header-logo {
            height: 44px;
            width: auto;
            max-width: min(200px, 42vw);
            object-fit: contain;
            object-position: left center;
            display: block;
        }

        .menu-toggle {
            display: none;
            background: var(--brand-navy);
            color: #fff;
            border: 0;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 16px;
            line-height: 1;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.45);
            z-index: 40;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
        }

        .mobile-sync-scroll {
            display: none;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 10px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: 0;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }

        .action-group {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .actions-cell {
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--brand-accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--brand-accent-hover);
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-muted {
            background: #475569;
            color: #fff;
        }

        .logout-form .btn {
            background: #dc2626;
            color: #fff;
        }

        .logout-form .btn:hover {
            background: #b91c1c;
        }

        .status {
            display: inline-block;
            border-radius: 999px;
            font-size: 12px;
            padding: 4px 8px;
        }

        .status-unread {
            background: #dcfce7;
            color: #166534;
        }

        .status-read {
            background: #e2e8f0;
            color: #334155;
        }

        .status-review {
            background: var(--primary-soft);
            color: var(--secondary);
        }

        .flash {
            background: #dcfce7;
            color: #166534;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .flash-warning {
            background: var(--primary-soft);
            color: var(--secondary);
            border: 1px solid var(--primary);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .error {
            color: #b91c1c;
            font-size: 14px;
            margin-top: 6px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .detail-list {
            display: grid;
            gap: 10px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            gap: 12px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }

        .detail-label {
            color: #475569;
            font-weight: 700;
        }

        .detail-value {
            color: #0f172a;
            word-break: break-word;
            white-space: normal;
        }

        @media (max-width: 1100px) {
            .body-grid {
                grid-template-columns: 200px 1fr;
            }

            main {
                padding: 18px;
            }
        }

        @media (max-width: 900px) {
            .body-grid {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            aside {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 270px;
                padding: 14px;
                z-index: 80;
                transform: translateX(-100%);
                transition: transform .25s ease;
            }

            aside nav {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
                margin-top: 48px;
            }

            aside a {
                white-space: normal;
                background: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.12);
            }

            th,
            td {
                white-space: nowrap;
            }

            .menu-toggle {
                display: inline-block;
            }

            .table-wrap {
                overflow-x: scroll;
                scrollbar-width: thin;
                scrollbar-color: #94a3b8 #e5e7eb;
                margin-bottom: 4px;
            }

            .table-wrap::-webkit-scrollbar {
                height: 12px;
            }

            .table-wrap::-webkit-scrollbar-track {
                background: #e5e7eb;
                border-radius: 8px;
            }

            .table-wrap::-webkit-scrollbar-thumb {
                background: #94a3b8;
                border-radius: 8px;
            }

            .mobile-sync-scroll {
                display: block;
                width: 100%;
                overflow-x: scroll;
                overflow-y: hidden;
                height: 12px;
                margin-top: 2px;
                scrollbar-width: thin;
                scrollbar-color: #94a3b8 #e5e7eb;
            }

            .mobile-sync-scroll-inner {
                height: 1px;
            }

            .mobile-sync-scroll::-webkit-scrollbar {
                height: 12px;
            }

            .mobile-sync-scroll::-webkit-scrollbar-track {
                background: #e5e7eb;
                border-radius: 8px;
            }

            .mobile-sync-scroll::-webkit-scrollbar-thumb {
                background: #94a3b8;
                border-radius: 8px;
            }

            .sidebar-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-open aside {
                transform: translateX(0);
            }

            .sidebar-open .sidebar-overlay {
                display: block;
            }

            .top-header-title {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .top-header {
                gap: 10px;
            }

            .wrap {
                min-width: 0;
            }

            main {
                padding: 14px;
            }

            aside nav {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 12px;
            }

            .header h1 {
                font-size: 22px;
                margin: 0;
            }

            table {
                min-width: 760px;
            }
        }

        @media (max-width: 640px) {
            .top-header {
                padding: 8px 12px;
            }

            .top-header-title {
                max-width: 170px;
            }

            .top-header-logo {
                height: 40px;
            }

            .btn {
                padding: 7px 10px;
                font-size: 13px;
            }

            .logout-form .btn {
                padding: 7px 10px;
            }

            .card {
                border-radius: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top-header">
            <div class="top-header-left">
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu">☰</button>
                <a href="{{ route('admin.dashboard') }}" class="top-header-title" aria-label="{{ config('app.name') }} admin">
                    <img src="{{ $adminHeaderLogoUrl ?? \App\Models\SiteDetail::headerLogoAssetUrl() }}" alt="{{ config('app.name') }}" class="top-header-logo">
                </a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn btn-muted">Logout</button>
            </form>
        </div>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <div class="body-grid">
            <aside>
                <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Close menu">×</button>
                <nav>
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a>
                    <a class="{{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}" href="{{ route('admin.contact-messages.index') }}">Contact form</a>
                    <a class="{{ request()->routeIs('admin.quote-requests.*') ? 'active' : '' }}" href="{{ route('admin.quote-requests.index') }}">Get a quote</a>
                    <a class="{{ request()->routeIs('admin.home-sections.*') ? 'active' : '' }}" href="{{ route('admin.home-sections.index') }}">Home Sections</a>
                    <a class="{{ request()->routeIs('admin.site-details.*') ? 'active' : '' }}" href="{{ route('admin.site-details.edit') }}">Site Details</a>
                    <a class="{{ request()->routeIs('admin.about-page.*') ? 'active' : '' }}" href="{{ route('admin.about-page.edit') }}">About Us page</a>
                    <a class="{{ request()->routeIs('admin.menus.index') || request()->routeIs('admin.menus.create') || request()->routeIs('admin.menus.page-sections.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">All menus (list)</a>
                    <a class="{{ request()->routeIs('admin.sub-menus.index') || request()->routeIs('admin.sub-menus.create') || request()->routeIs('admin.sub-menus.page-sections.*') ? 'active' : '' }}" href="{{ route('admin.sub-menus.index') }}">All sub-menus (list)</a>

                    @php($routeMenu = request()->route('menu'))
                    @php($routeSub = request()->route('sub_menu'))

                    @foreach (($adminSidebarMenus ?? collect()) as $sidebarMenu)
                        @if ($sidebarMenu->subMenus->isEmpty())
                            <a class="{{ (request()->routeIs('admin.menus.edit') || request()->routeIs('admin.menus.page-sections.*')) && $routeMenu && (int) $routeMenu->id === (int) $sidebarMenu->id ? 'active' : '' }}"
                                href="{{ route('admin.menus.page-sections.index', $sidebarMenu) }}">{{ $sidebarMenu->label }}</a>
                        @else
                            @php($openSidebarGroup = (request()->routeIs('admin.menus.edit') && $routeMenu && (int) $routeMenu->id === (int) $sidebarMenu->id)
                                || (request()->routeIs('admin.menus.page-sections.*') && $routeMenu && (int) $routeMenu->id === (int) $sidebarMenu->id)
                                || (request()->routeIs('admin.sub-menus.edit') && $routeSub && (int) $routeSub->menu_id === (int) $sidebarMenu->id)
                                || (request()->routeIs('admin.sub-menus.page-sections.*') && $routeSub && (int) $routeSub->menu_id === (int) $sidebarMenu->id))
                            <details class="nav-group" @if ($openSidebarGroup) open @endif>
                                <summary>
                                    <span>{{ $sidebarMenu->label }}</span>
                                    <span aria-hidden="true" style="opacity:.85;">▾</span>
                                </summary>
                                <div class="nav-sub">
                                    <a class="parent-page {{ (request()->routeIs('admin.menus.edit') || request()->routeIs('admin.menus.page-sections.*')) && $routeMenu && (int) $routeMenu->id === (int) $sidebarMenu->id ? 'active' : '' }}"
                                        href="{{ route('admin.menus.page-sections.index', $sidebarMenu) }}"
                                        title="Page sections for this parent menu">{{ $sidebarMenu->label }}</a>
                                    @foreach ($sidebarMenu->subMenus as $sidebarSub)
                                        <a class="{{ (request()->routeIs('admin.sub-menus.edit') || request()->routeIs('admin.sub-menus.page-sections.*')) && $routeSub && (int) $routeSub->id === (int) $sidebarSub->id ? 'active' : '' }}"
                                            href="{{ route('admin.sub-menus.page-sections.index', $sidebarSub) }}">{{ $sidebarSub->label }}</a>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    @endforeach
                </nav>
            </aside>
            <main>

                @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
                @endif
                @if (session('warning'))
                <div class="flash-warning">{{ session('warning') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script>
        (() => {
            const root = document.querySelector('.wrap');
            const menuToggle = document.getElementById('menuToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const sidebarClose = document.getElementById('sidebarClose');

            if (!root || !menuToggle || !overlay) return;

            const closeSidebar = () => root.classList.remove('sidebar-open');
            const openSidebar = () => root.classList.add('sidebar-open');

            menuToggle.addEventListener('click', () => {
                if (root.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) closeSidebar();
            });

            const setupMobileSyncScrollbars = () => {
                document.querySelectorAll('.table-wrap').forEach((wrap) => {
                    const table = wrap.querySelector('table');
                    if (!table) return;

                    let sync = wrap.nextElementSibling;
                    if (!sync || !sync.classList.contains('mobile-sync-scroll')) {
                        sync = document.createElement('div');
                        sync.className = 'mobile-sync-scroll';
                        const inner = document.createElement('div');
                        inner.className = 'mobile-sync-scroll-inner';
                        sync.appendChild(inner);
                        wrap.insertAdjacentElement('afterend', sync);
                    }

                    const inner = sync.querySelector('.mobile-sync-scroll-inner');
                    if (!inner) return;
                    inner.style.width = `${table.scrollWidth}px`;

                    const active = window.innerWidth <= 900;
                    sync.style.display = active ? 'block' : 'none';

                    let syncing = false;
                    wrap.addEventListener('scroll', () => {
                        if (!active || syncing) return;
                        syncing = true;
                        sync.scrollLeft = wrap.scrollLeft;
                        syncing = false;
                    }, {
                        passive: true
                    });

                    sync.addEventListener('scroll', () => {
                        if (!active || syncing) return;
                        syncing = true;
                        wrap.scrollLeft = sync.scrollLeft;
                        syncing = false;
                    }, {
                        passive: true
                    });
                });
            };

            setupMobileSyncScrollbars();
            window.addEventListener('resize', setupMobileSyncScrollbars);
        })();
    </script>
</body>

</html>