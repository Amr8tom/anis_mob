<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'بوابة الشركاء — أنيس')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --upwork-green:       #14a800;
            --upwork-green-dark:  #108a00;
            --upwork-green-soft:  #f0faf0;
            --upwork-slate:       #001e00;
            --upwork-muted:       #5e6d55;
            --upwork-bg:          #f7f9f7;
            --upwork-card-bg:     #ffffff;
            --upwork-border:      #e4ece4;
            --upwork-input-border:#d5e2d5;
            --upwork-error:       #df2020;
            --upwork-error-bg:    #fff5f5;
            --upwork-success:     #14a800;
            --upwork-success-bg:  #f4fdf4;
            --upwork-blue:        #1d7def;
            --font-family:        'Tajawal', sans-serif;
            --radius-sm:          8px;
            --radius-md:          12px;
            --radius-lg:          16px;
            --shadow:             0 1px 6px rgba(0,0,0,0.04);
            --transition:         all 0.2s ease-in-out;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family);
            background-color: var(--upwork-bg);
            color: var(--upwork-slate);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ═══════════════════════════════════════════
           NAV — redesigned for clarity & UX
        ═══════════════════════════════════════════ */
        header.top-nav {
            background: #ffffff;
            border-bottom: 1px solid var(--upwork-border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0,0,0,0.055);
        }

        .nav-container {
            max-width: 1260px;
            margin: 0 auto;
            padding: 0 28px;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            height: 68px;
        }

        /* ── Brand ── */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 900;
            font-size: 20px;
            color: var(--upwork-slate);
            text-decoration: none;
            flex-shrink: 0;
            letter-spacing: -0.3px;
        }

        .brand-logo .logo-mark {
            background: linear-gradient(135deg, #1ec600 0%, #0d8c00 100%);
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 19px;
            font-weight: 900;
            box-shadow: 0 3px 10px rgba(20,168,0,0.35);
            flex-shrink: 0;
        }

        .brand-name { letter-spacing: -0.5px; }

        /* ── Nav Links (centre) ── */
        .nav-links {
            display: flex;
            align-items: stretch;
            gap: 0;
            margin: 0 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            gap: 3px;
            padding: 0 15px;
            text-decoration: none;
            color: var(--upwork-muted);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.15px;
            position: relative;
            transition: color 0.18s ease;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .nav-link i {
            font-size: 18px;
            transition: transform 0.18s ease;
            color: inherit;
        }

        .nav-link:hover {
            color: var(--upwork-slate);
        }

        .nav-link:hover i {
            transform: translateY(-1px);
        }

        .nav-link.active-nav {
            color: var(--upwork-green) !important;
            border-bottom-color: var(--upwork-green);
        }

        /* ── Right side ── */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        /* Workspace pill */
        .workspace-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--upwork-green-soft);
            color: var(--upwork-green-dark);
            font-size: 13px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 99px;
            border: 1.5px solid rgba(20,168,0,0.18);
            max-width: 190px;
        }

        .ws-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--upwork-green);
            flex-shrink: 0;
            animation: pulse-dot 2.4s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%,100% { box-shadow: 0 0 0 0px rgba(20,168,0,0.25); }
            50%      { box-shadow: 0 0 0 4px rgba(20,168,0,0.10); }
        }

        .workspace-badge .ws-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Nav vertical divider */
        .nav-divider {
            width: 1px;
            height: 28px;
            background: var(--upwork-border);
            flex-shrink: 0;
        }

        /* User area */
        .user-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1ec600 0%, #0d8c00 100%);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(20,168,0,0.3);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1px solid var(--upwork-border);
            color: var(--upwork-muted);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 99px;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-logout:hover {
            border-color: var(--upwork-error);
            color: var(--upwork-error);
            background: #fff5f5;
        }

        /* ═══════════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════════ */
        main.app-main {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 24px;
        }

        h1, h2, h3, h4 { font-weight: 700; color: var(--upwork-slate); }

        .page-title    { font-size: 28px; margin-bottom: 8px; }
        .page-subtitle { font-size: 16px; color: var(--upwork-muted); margin-bottom: 30px; }

        /* Settings page wrapper */
        .settings-container { max-width: 900px; }

        /* Cards */
        .card {
            background: var(--upwork-card-bg);
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-md);
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--upwork-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error   { background: var(--upwork-error-bg);   color: var(--upwork-error);   border: 1px solid rgba(223,32,32,0.15); }
        .alert-success { background: var(--upwork-success-bg); color: var(--upwork-success); border: 1px solid rgba(20,168,0,0.15); }

        /* Grids */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr;       gap: 24px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr;   gap: 24px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }

        /* Forms */
        label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--upwork-slate);
        }

        .form-control,
        .form-input,
        .form-select {
            width: 100%;
            padding: 11px 14px;
            font-family: var(--font-family);
            font-size: 15px;
            border: 1px solid var(--upwork-input-border);
            border-radius: var(--radius-sm);
            background: #fff;
            color: var(--upwork-slate);
            transition: var(--transition);
            margin-bottom: 16px;
        }

        .form-control:focus,
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--upwork-green);
            box-shadow: 0 0 0 3px rgba(20,168,0,0.1);
        }

        .form-error { color: var(--upwork-error); font-size: 13px; font-weight: 600; margin-top: 6px; }

        .btn-primary,
        .btn-submit {
            background: var(--upwork-green);
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover,
        .btn-submit:hover { background: var(--upwork-green-dark); }

        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Tables */
        .table-responsive { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; text-align: right; }
        thead tr { border-bottom: 2px solid var(--upwork-border); background: var(--upwork-bg); }
        th { padding: 12px 16px; font-size: 13px; font-weight: 700; color: var(--upwork-muted); }
        td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid var(--upwork-border); }

        /* Pagination (RTL, upwork theme) */
        .uw-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--upwork-border);
        }
        .uw-pagination--single { justify-content: flex-end; border-top: none; padding-top: 0; }
        .uw-pagination__summary { font-size: 13px; color: var(--upwork-muted); }
        .uw-pagination__summary strong { color: var(--upwork-slate); font-weight: 800; }
        .uw-pagination__list {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
        }
        .uw-page__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-sm);
            background: var(--upwork-card-bg);
            color: var(--upwork-slate);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        a.uw-page__link:hover {
            border-color: var(--upwork-green);
            color: var(--upwork-green-dark);
            background: var(--upwork-green-soft);
        }
        .uw-page--active .uw-page__link {
            background: var(--upwork-green);
            border-color: var(--upwork-green);
            color: #fff;
            box-shadow: 0 2px 8px rgba(20,168,0,0.25);
        }
        .uw-page--disabled .uw-page__link {
            opacity: 0.45;
            cursor: not-allowed;
            background: var(--upwork-bg);
        }
        .uw-page__ellipsis { border: none; background: transparent; min-width: 24px; padding: 0; }

        @media (max-width: 600px) {
            .uw-pagination { justify-content: center; }
            .uw-pagination__summary { width: 100%; text-align: center; }
        }

        @media (max-width: 960px) {
            .nav-links { display: none; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>

<header class="top-nav">
    <div class="nav-container">

        {{-- Brand --}}
        <a href="{{ route('landing') }}" class="brand-logo">
            <span class="logo-mark">أ</span>
            <span class="brand-name">أنيس شريك</span>
        </a>

        {{-- Centre nav links --}}
        @auth
            @if(Auth::user()->ownedWorkspace)
            <nav class="nav-links">
                @php
                    $navItems = [
                        ['route' => 'workspace.sessions.index',  'match' => 'workspace.sessions.*',   'icon' => 'fa-chalkboard-user',      'label' => 'الجلسات'],
                        ['route' => 'workspace.clients.index',   'match' => 'workspace.clients.*',    'icon' => 'fa-users-viewfinder',     'label' => 'الزوار'],
                        ['route' => 'workspace.visits.index',    'match' => 'workspace.visits.*',     'icon' => 'fa-right-to-bracket',     'label' => 'تسجيل الزوار'],
                        ['route' => 'workspace.subscriptions.index', 'match' => 'workspace.subscriptions.*', 'icon' => 'fa-ticket',          'label' => 'الاشتراكات الخاصة'],
                        ['route' => 'workspace.rooms.index',     'match' => 'workspace.rooms.*',      'icon' => 'fa-door-open',            'label' => 'حجوزات الغرف'],
                        ['route' => 'workspace.settings.edit',   'match' => 'workspace.settings*',   'icon' => 'fa-gear',                 'label' => 'الإعدادات'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ request()->routeIs($item['match']) ? 'active-nav' : '' }}">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            @endif
        @endauth

        {{-- Right: workspace badge + user --}}
        @auth
        <div class="nav-right">
            @if(Auth::user()->ownedWorkspace)
                <span class="workspace-badge">
                    <span class="ws-dot"></span>
                    <span class="ws-name">{{ Auth::user()->ownedWorkspace->name }}</span>
                </span>
                <div class="nav-divider"></div>
            @endif

            <div class="user-area">
                <div class="user-avatar">{{ mb_substr(Auth::user()->full_name, 0, 1, 'utf-8') }}</div>
                <form action="{{ route('workspace.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        خروج
                    </button>
                </form>
            </div>
        </div>
        @endauth

    </div>
</header>

<main class="app-main">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-xmark"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @yield('content')
</main>

@yield('scripts')
</body>
</html>
