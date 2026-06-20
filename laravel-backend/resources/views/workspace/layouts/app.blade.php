<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#14a800">
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
            --upwork-bg:          #f6f8f6;
            --upwork-card-bg:     #ffffff;
            --upwork-border:      #e7eee7;
            --upwork-input-border:#d5e2d5;
            --upwork-error:       #df2020;
            --upwork-error-bg:    #fff5f5;
            --upwork-success:     #14a800;
            --upwork-success-bg:  #f4fdf4;
            --upwork-blue:        #1d7def;
            --font-family:        'Tajawal', sans-serif;

            /* 4/8pt spacing scale */
            --s-1: 4px;  --s-2: 8px;  --s-3: 12px; --s-4: 16px;
            --s-5: 20px; --s-6: 24px; --s-8: 32px; --s-10: 40px;

            --radius-sm:          10px;
            --radius-md:          14px;
            --radius-lg:          20px;

            --shadow-xs:  0 1px 2px rgba(16,40,16,0.04);
            --shadow:     0 1px 3px rgba(16,40,16,0.05), 0 1px 2px rgba(16,40,16,0.04);
            --shadow-md:  0 4px 12px rgba(16,40,16,0.06), 0 2px 4px rgba(16,40,16,0.04);
            --shadow-lg:  0 12px 32px rgba(16,40,16,0.10);

            --transition:         all 0.2s ease-in-out;
            --sidebar-w:          268px;
            --bottom-nav-h:       64px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }

        body {
            font-family: var(--font-family);
            background-color: var(--upwork-bg);
            color: var(--upwork-slate);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        a:focus-visible, button:focus-visible,
        input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid var(--upwork-green);
            outline-offset: 2px;
        }

        /* ═══════════════════════════════════════════
           DESKTOP SIDEBAR (right, RTL)
        ═══════════════════════════════════════════ */
        .app-sidebar {
            position: fixed;
            top: 0; right: 0; bottom: 0;
            width: var(--sidebar-w);
            background: #fff;
            border-left: 1px solid var(--upwork-border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: -1px 0 3px rgba(16,40,16,0.03);
        }

        .sb-brand {
            display: flex; align-items: center; gap: 11px;
            padding: 22px 22px 18px;
            font-weight: 900; font-size: 20px; color: var(--upwork-slate);
            text-decoration: none; letter-spacing: -0.4px;
            border-bottom: 1px solid var(--upwork-border);
        }
        .sb-brand .logo-mark {
            background: linear-gradient(135deg, #1ec600 0%, #0d8c00 100%);
            color: #fff; width: 40px; height: 40px; border-radius: 11px;
            display: grid; place-items: center; font-size: 19px; font-weight: 900;
            box-shadow: 0 3px 10px rgba(20,168,0,0.35); flex-shrink: 0;
        }

        .sb-nav { flex: 1; overflow-y: auto; padding: 16px 14px; display: flex; flex-direction: column; gap: 4px; }
        .sb-section-label { font-size: 11px; font-weight: 800; color: var(--upwork-muted); letter-spacing: 0.4px; padding: 6px 12px; text-transform: uppercase; opacity: 0.8; }

        .side-link {
            display: flex; align-items: center; gap: 13px;
            padding: 12px 14px; border-radius: var(--radius-sm);
            text-decoration: none; color: var(--upwork-muted);
            font-size: 15px; font-weight: 700; position: relative;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .side-link i { width: 22px; text-align: center; font-size: 18px; flex-shrink: 0; transition: transform 0.15s ease; }
        .side-link:hover { background: var(--upwork-bg); color: var(--upwork-slate); }
        .side-link.active-nav { background: var(--upwork-green-soft); color: var(--upwork-green-dark); }
        .side-link.active-nav::before {
            content: ''; position: absolute; top: 9px; bottom: 9px; right: 0;
            width: 4px; border-radius: 4px 0 0 4px; background: var(--upwork-green);
        }
        .side-link.active-nav i { color: var(--upwork-green); }

        /* Sidebar footer: workspace + user */
        .sb-footer { border-top: 1px solid var(--upwork-border); padding: 14px; }
        .sb-ws {
            display: flex; align-items: center; gap: 8px;
            background: var(--upwork-green-soft); color: var(--upwork-green-dark);
            font-size: 12.5px; font-weight: 700; padding: 8px 12px;
            border-radius: var(--radius-sm); border: 1px solid rgba(20,168,0,0.16);
            margin-bottom: 12px;
        }
        .ws-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--upwork-green); flex-shrink: 0; animation: pulse-dot 2.4s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { box-shadow: 0 0 0 0 rgba(20,168,0,0.25); } 50% { box-shadow: 0 0 0 4px rgba(20,168,0,0.10); } }
        .sb-ws .ws-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .sb-user { display: flex; align-items: center; gap: 10px; }
        .user-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg, #1ec600 0%, #0d8c00 100%);
            color: #fff; display: grid; place-items: center;
            font-weight: 800; font-size: 15px; flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(20,168,0,0.3);
        }
        .sb-user .u-meta { flex: 1; min-width: 0; }
        .sb-user .u-name { font-size: 13.5px; font-weight: 800; color: var(--upwork-slate); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .btn-logout {
            display: inline-flex; align-items: center; justify-content: center;
            background: none; border: 1px solid var(--upwork-border);
            color: var(--upwork-muted); font-family: inherit;
            width: 38px; height: 38px; border-radius: 10px;
            cursor: pointer; transition: var(--transition); flex-shrink: 0;
        }
        .btn-logout span { display: none; }
        .btn-logout:hover { border-color: var(--upwork-error); color: var(--upwork-error); background: #fff5f5; }

        /* ═══════════════════════════════════════════
           MAIN  (full remaining width)
        ═══════════════════════════════════════════ */
        .app-main {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            padding: 36px 40px;
        }
        .content-wrap { width: 100%; max-width: 1480px; }

        h1, h2, h3, h4 { font-weight: 800; color: var(--upwork-slate); letter-spacing: -0.2px; }
        .page-title    { font-size: 28px; margin-bottom: 8px; }
        .page-subtitle { font-size: 16px; color: var(--upwork-muted); margin-bottom: 30px; font-weight: 500; }
        .settings-container { max-width: 1100px; }

        /* Mobile top bar (hidden on desktop) */
        .mobile-topbar { display: none; }

        /* Cards */
        .card {
            background: var(--upwork-card-bg);
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-md);
            padding: 28px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }
        .card-title { font-size: 20px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--upwork-border); display: flex; align-items: center; gap: 10px; }

        /* Alerts */
        .alert { padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 24px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow-xs); }
        .alert i { font-size: 18px; flex-shrink: 0; }
        .alert-error   { background: var(--upwork-error-bg);   color: var(--upwork-error);   border: 1px solid rgba(223,32,32,0.15); }
        .alert-success { background: var(--upwork-success-bg); color: var(--upwork-success); border: 1px solid rgba(20,168,0,0.15); }

        /* Grids */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr;       gap: 24px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr;   gap: 24px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }

        /* "Manage" block: list/table = wide primary column, add-form = side panel.
           RTL: the 1fr (main/table) sits on the right, the form on the left. */
        .manage-grid { display: grid; grid-template-columns: 1fr 340px; gap: 22px; align-items: start; }
        .manage-aside { position: sticky; top: 24px; }
        .manage-form-panel {
            background: var(--upwork-bg);
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-md);
            padding: 18px;
        }
        .manage-form-panel h4 { font-size: 15px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .manage-form-panel .btn-primary, .manage-form-panel .btn-submit { width: 100%; }
        .manage-main .empty-state {
            text-align: center; color: var(--upwork-muted);
            padding: 44px 16px; border: 1px dashed var(--upwork-border);
            border-radius: var(--radius-md); background: #fff;
        }
        .manage-main .empty-state i { font-size: 30px; opacity: 0.4; display: block; margin-bottom: 10px; }

        /* Forms */
        label { display: block; font-size: 14px; font-weight: 700; margin-bottom: 6px; color: var(--upwork-slate); }
        .form-control, .form-input, .form-select {
            width: 100%; padding: 12px 14px; font-family: var(--font-family); font-size: 15px;
            border: 1px solid var(--upwork-input-border); border-radius: var(--radius-sm); background: #fff;
            color: var(--upwork-slate); transition: var(--transition); margin-bottom: 16px;
        }
        .form-control:focus, .form-input:focus, .form-select:focus { outline: none; border-color: var(--upwork-green); box-shadow: 0 0 0 3px rgba(20,168,0,0.12); }
        .form-error { color: var(--upwork-error); font-size: 13px; font-weight: 600; margin-top: 6px; }

        .btn-primary, .btn-submit {
            background: var(--upwork-green); color: #fff; font-family: inherit; font-size: 15px; font-weight: 700;
            padding: 12px 24px; border: none; border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition);
            display: inline-flex; justify-content: center; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(20,168,0,0.18);
        }
        .btn-primary:hover, .btn-submit:hover { background: var(--upwork-green-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(20,168,0,0.28); }
        .btn-primary:active, .btn-submit:active { transform: translateY(0); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Tables */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; text-align: right; }
        thead tr { border-bottom: 2px solid var(--upwork-border); background: var(--upwork-bg); }
        th { padding: 12px 16px; font-size: 13px; font-weight: 700; color: var(--upwork-muted); white-space: nowrap; }
        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid var(--upwork-border); }
        tbody tr { transition: background 0.15s ease; }
        tbody tr:hover { background: var(--upwork-green-soft); }

        /* Pagination */
        .uw-pagination { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--upwork-border); }
        .uw-pagination--single { justify-content: flex-end; border-top: none; padding-top: 0; }
        .uw-pagination__summary { font-size: 13px; color: var(--upwork-muted); }
        .uw-pagination__summary strong { color: var(--upwork-slate); font-weight: 800; }
        .uw-pagination__list { display: flex; align-items: center; gap: 6px; list-style: none; margin: 0; padding: 0; flex-wrap: wrap; }
        .uw-page__link { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid var(--upwork-border); border-radius: var(--radius-sm); background: var(--upwork-card-bg); color: var(--upwork-slate); font-weight: 700; font-size: 14px; text-decoration: none; transition: all 0.15s ease; cursor: pointer; }
        a.uw-page__link:hover { border-color: var(--upwork-green); color: var(--upwork-green-dark); background: var(--upwork-green-soft); }
        .uw-page--active .uw-page__link { background: var(--upwork-green); border-color: var(--upwork-green); color: #fff; box-shadow: 0 2px 8px rgba(20,168,0,0.25); }
        .uw-page--disabled .uw-page__link { opacity: 0.45; cursor: not-allowed; background: var(--upwork-bg); }
        .uw-page__ellipsis { border: none; background: transparent; min-width: 24px; padding: 0; }

        /* Mobile bottom nav (hidden on desktop) */
        .bottom-nav { display: none; }
        .more-sheet-overlay { display: none; }

        @media (max-width: 600px) {
            .uw-pagination { justify-content: center; }
            .uw-pagination__summary { width: 100%; text-align: center; }
        }

        /* ═══════════════════════════════════════════
           MOBILE / TABLET (≤960): hide sidebar → bottom nav
        ═══════════════════════════════════════════ */
        @media (max-width: 960px) {
            .app-sidebar { display: none; }
            .app-main { margin-right: 0; padding: 20px 16px calc(var(--bottom-nav-h) + env(safe-area-inset-bottom) + 24px); }
            .content-wrap { max-width: none; }

            .mobile-topbar {
                display: flex; align-items: center; justify-content: space-between; gap: 12px;
                height: 56px; padding: 0 16px; padding-top: env(safe-area-inset-top);
                background: rgba(255,255,255,0.9);
                backdrop-filter: saturate(180%) blur(12px); -webkit-backdrop-filter: saturate(180%) blur(12px);
                border-bottom: 1px solid var(--upwork-border); position: sticky; top: 0; z-index: 100;
            }
            .mobile-topbar .mt-ws { display: flex; align-items: center; gap: 7px; font-weight: 800; font-size: 14px; color: var(--upwork-slate); overflow: hidden; }
            .mobile-topbar .mt-ws .ws-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 52vw; }

            .page-title { font-size: 23px; }
            .page-subtitle { font-size: 15px; margin-bottom: 22px; }
            .card { padding: 20px; }
            .card-title { font-size: 18px; margin-bottom: 18px; padding-bottom: 14px; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; gap: 16px; }
            /* Stack the manage block: add-form first, then the list. */
            .manage-grid { grid-template-columns: 1fr; gap: 16px; }
            .manage-aside { position: static; order: -1; }

            .bottom-nav {
                display: flex; position: fixed; bottom: 0; right: 0; left: 0; z-index: 200;
                background: rgba(255,255,255,0.94); backdrop-filter: saturate(180%) blur(14px); -webkit-backdrop-filter: saturate(180%) blur(14px);
                border-top: 1px solid var(--upwork-border); box-shadow: 0 -4px 20px rgba(16,40,16,0.06);
                padding-bottom: env(safe-area-inset-bottom); height: calc(var(--bottom-nav-h) + env(safe-area-inset-bottom));
            }
            .bn-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 3px; text-decoration: none; color: var(--upwork-muted); font-size: 10.5px; font-weight: 700; padding: 8px 2px; min-height: 44px; background: none; border: none; cursor: pointer; font-family: inherit; position: relative; transition: color 0.15s ease; }
            .bn-item i { font-size: 20px; transition: transform 0.15s ease; }
            .bn-item.active { color: var(--upwork-green); }
            .bn-item.active i { transform: translateY(-1px); }
            .bn-item.active::before { content: ''; position: absolute; top: 0; right: 22%; left: 22%; height: 3px; border-radius: 0 0 4px 4px; background: var(--upwork-green); }

            .more-sheet-overlay { display: block; position: fixed; inset: 0; z-index: 300; background: rgba(0,20,0,0.42); opacity: 0; visibility: hidden; transition: opacity 0.25s ease, visibility 0.25s ease; }
            .more-sheet-overlay.open { opacity: 1; visibility: visible; }
            .more-sheet { position: absolute; right: 0; left: 0; bottom: 0; background: #fff; border-radius: var(--radius-lg) var(--radius-lg) 0 0; padding: 8px 16px calc(20px + env(safe-area-inset-bottom)); transform: translateY(100%); transition: transform 0.28s cubic-bezier(.2,.8,.2,1); box-shadow: var(--shadow-lg); }
            .more-sheet-overlay.open .more-sheet { transform: translateY(0); }
            .more-sheet .grip { width: 40px; height: 4px; border-radius: 99px; background: var(--upwork-border); margin: 8px auto 14px; }
            .more-sheet .ms-title { font-size: 13px; font-weight: 700; color: var(--upwork-muted); margin: 4px 6px 10px; }
            .ms-item { display: flex; align-items: center; gap: 14px; padding: 14px 10px; text-decoration: none; color: var(--upwork-slate); font-weight: 700; font-size: 15px; border-radius: var(--radius-sm); transition: background 0.15s ease; }
            .ms-item:active { background: var(--upwork-bg); }
            .ms-item i { width: 22px; text-align: center; font-size: 18px; color: var(--upwork-muted); }
            .ms-item.active { color: var(--upwork-green); } .ms-item.active i { color: var(--upwork-green); }
            .ms-item.danger { color: var(--upwork-error); } .ms-item.danger i { color: var(--upwork-error); }
            .ms-divider { height: 1px; background: var(--upwork-border); margin: 8px 0; }
            .ms-logout-form { margin: 0; }
            .ms-logout-form button { width: 100%; background: none; border: none; cursor: pointer; font-family: inherit; }
        }

        /* Guest pages (login/register/pending) have no sidebar */
        body.guest .app-main { margin-right: 0; }
        body.guest .content-wrap { max-width: 560px; margin: 0 auto; }
    </style>
    @yield('styles')
</head>
<body class="@guest guest @endguest">

@php
    $navItems = [
        ['route' => 'workspace.sessions.index',      'match' => 'workspace.sessions.*',      'icon' => 'fa-chalkboard-user',  'label' => 'الجلسات'],
        ['route' => 'workspace.private-sessions.index', 'match' => 'workspace.private-sessions.*', 'icon' => 'fa-qrcode', 'label' => 'جلسات خاصة'],
        ['route' => 'workspace.clients.index',        'match' => 'workspace.clients.*',       'icon' => 'fa-users-viewfinder', 'label' => 'السجل'],
        ['route' => 'workspace.visits.index',         'match' => 'workspace.visits.*',        'icon' => 'fa-right-to-bracket', 'label' => 'تسجيل الزوار'],
        ['route' => 'workspace.subscriptions.index',  'match' => 'workspace.subscriptions.*', 'icon' => 'fa-ticket',           'label' => 'الاشتراكات'],
        ['route' => 'workspace.rooms.index',          'match' => 'workspace.rooms.*',         'icon' => 'fa-door-open',        'label' => 'حجوزات الغرف'],
        ['route' => 'workspace.settings.edit',        'match' => 'workspace.settings*',       'icon' => 'fa-gear',             'label' => 'الإعدادات'],
    ];
    $bottomPrimary = array_slice($navItems, 0, 4);
    $bottomMore    = array_slice($navItems, 4);
    $hasWorkspace  = Auth::check() && Auth::user()->ownedWorkspace;
@endphp

{{-- ═══ DESKTOP SIDEBAR ═══ --}}
@if($hasWorkspace)
<aside class="app-sidebar">
    <a href="{{ route('landing') }}" class="sb-brand">
        <span class="logo-mark">أ</span>
        <span>أنيس شريك</span>
    </a>

    <nav class="sb-nav">
        <div class="sb-section-label">القائمة</div>
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" class="side-link {{ request()->routeIs($item['match']) ? 'active-nav' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sb-footer">
        <span class="sb-ws">
            <span class="ws-dot"></span>
            <span class="ws-name">{{ Auth::user()->ownedWorkspace->name }}</span>
        </span>
        <div class="sb-user">
            <div class="user-avatar">{{ mb_substr(Auth::user()->full_name, 0, 1, 'utf-8') }}</div>
            <div class="u-meta"><div class="u-name">{{ Auth::user()->full_name }}</div></div>
            <form action="{{ route('workspace.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" title="تسجيل الخروج" aria-label="تسجيل الخروج">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
@endif

{{-- ═══ MOBILE TOP BAR ═══ --}}
@auth
<div class="mobile-topbar">
    @if($hasWorkspace)
        <span class="mt-ws"><span class="ws-dot"></span><span class="ws-name">{{ Auth::user()->ownedWorkspace->name }}</span></span>
    @else
        <a href="{{ route('landing') }}" class="sb-brand" style="border:none;padding:0;font-size:17px;"><span class="logo-mark" style="width:32px;height:32px;font-size:16px;">أ</span> أنيس شريك</a>
    @endif
    <div class="user-avatar" style="width:32px;height:32px;font-size:13px;">{{ mb_substr(Auth::user()->full_name, 0, 1, 'utf-8') }}</div>
</div>
@endauth

<main class="app-main">
    <div class="content-wrap">
        @if(session('success') && !request()->routeIs('workspace.visits.*'))
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i><div>{{ session('success') }}</div></div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fa-solid fa-circle-xmark"></i><div>{{ session('error') }}</div></div>
        @endif

        @yield('content')
    </div>
</main>

{{-- ═══ MOBILE BOTTOM TAB BAR ═══ --}}
@if($hasWorkspace)
<nav class="bottom-nav">
    @foreach($bottomPrimary as $item)
        <a href="{{ route($item['route']) }}" class="bn-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
            <i class="fa-solid {{ $item['icon'] }}"></i>
            <span>{{ \Illuminate\Support\Str::of($item['label'])->explode(' ')->first() }}</span>
        </a>
    @endforeach
    @php($moreActive = collect($bottomMore)->contains(fn ($i) => request()->routeIs($i['match'])))
    <button type="button" class="bn-item {{ $moreActive ? 'active' : '' }}" onclick="toggleMoreSheet(true)" aria-haspopup="true" aria-controls="moreSheet">
        <i class="fa-solid fa-ellipsis"></i>
        <span>المزيد</span>
    </button>
</nav>

<div class="more-sheet-overlay" id="moreSheet" onclick="if(event.target===this) toggleMoreSheet(false)">
    <div class="more-sheet" role="dialog" aria-modal="true" aria-label="قائمة إضافية">
        <div class="grip"></div>
        <div class="ms-title">المزيد من الخيارات</div>
        @foreach($bottomMore as $item)
            <a href="{{ route($item['route']) }}" class="ms-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
        <div class="ms-divider"></div>
        <form action="{{ route('workspace.logout') }}" method="POST" class="ms-logout-form">
            @csrf
            <button type="submit" class="ms-item danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</button>
        </form>
    </div>
</div>

<script>
    function toggleMoreSheet(open) {
        var el = document.getElementById('moreSheet');
        if (!el) return;
        el.classList.toggle('open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    }
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') toggleMoreSheet(false); });
</script>
@endif

@yield('scripts')
</body>
</html>
