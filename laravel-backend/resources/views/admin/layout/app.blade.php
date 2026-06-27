<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'بوابة الإدارة — أنيس')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Upwork Aesthetic Colors */
            --upwork-green: #14a800;
            --upwork-green-dark: #108a00;
            --upwork-green-soft: #f4fdf4;
            --upwork-slate: #001e00;
            --upwork-muted: #5e6d55;
            --upwork-bg: #f9fbf9;
            --upwork-card-bg: #ffffff;
            --upwork-border: #e4ece4;
            --upwork-input-border: #d5e2d5;
            --upwork-error: #df2020;
            --upwork-error-bg: #fff5f5;
            --upwork-success: #14a800;
            --upwork-success-bg: #f4fdf4;
            --admin-surface: #ffffff;
            --admin-surface-soft: #f5faf5;
            
            --font-family: 'Tajawal', sans-serif;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            --transition: all 0.2s ease-in-out;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--upwork-bg);
            color: var(--upwork-slate);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Nav Header */
        header.top-nav {
            background-color: var(--upwork-card-bg);
            border-bottom: 1px solid var(--upwork-border);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 70px;
        }

        .nav-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 70px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 22px;
            color: var(--upwork-slate);
            text-decoration: none;
            white-space: nowrap;
        }

        .brand-logo .logo-mark {
            background-color: var(--upwork-slate);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            display: grid;
            place-items: center;
            font-size: 20px;
            font-weight: 900;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 18px;
            min-width: 0;
        }

        .admin-nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .admin-nav-link {
            color: var(--upwork-muted);
            text-decoration: none;
            font-weight: 800;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 12px;
            border-radius: 999px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .admin-nav-link:hover {
            color: var(--upwork-green-dark);
            background: var(--upwork-green-soft);
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--upwork-slate);
            color: #ffffff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 16px;
        }

        .btn-logout {
            background: none;
            border: none;
            color: var(--upwork-muted);
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .btn-logout:hover {
            color: var(--upwork-error);
        }

        /* Main Content Grid */
        main.app-main {
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: clamp(18px, 3vw, 36px) 24px 48px;
        }

        /* Base Typography & Elements */
        h1, h2, h3, h4 {
            font-weight: 700;
            color: var(--upwork-slate);
        }

        .page-title {
            font-size: clamp(24px, 3vw, 34px);
            margin-bottom: 8px;
            font-weight: 900;
        }

        .page-subtitle {
            font-size: 16px;
            color: var(--upwork-muted);
            margin-bottom: 30px;
        }

        /* Cards - Upwork Style */
        .card {
            background-color: var(--upwork-card-bg);
            border: 1px solid var(--upwork-border);
            border-radius: var(--radius-md);
            padding: clamp(18px, 2.3vw, 28px);
            box-shadow: 0 16px 42px rgba(0, 30, 0, 0.06);
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

        /* Alert Messages */
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

        .alert-error {
            background-color: var(--upwork-error-bg);
            color: var(--upwork-error);
            border: 1px solid rgba(223, 32, 32, 0.15);
        }

        .alert-success {
            background-color: var(--upwork-success-bg);
            color: var(--upwork-success);
            border: 1px solid rgba(20, 168, 0, 0.15);
        }

        .active-nav {
            color: var(--upwork-green) !important;
            background: var(--upwork-green-soft);
            box-shadow: inset 0 -2px 0 var(--upwork-green);
        }
        
        .btn-primary {
            background-color: var(--upwork-green);
            color: #fff;
            border: none;
            padding: 11px 20px;
            border-radius: 99px;
            font-weight: 800;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
        }
        
        .btn-primary:hover {
            background-color: var(--upwork-green-dark);
        }

        .form-input, .form-select {
            width: 100%;
            min-height: 46px;
            padding: 12px 16px;
            border: 1.5px solid var(--upwork-input-border);
            border-radius: 14px;
            font-family: inherit;
            font-size: 15px;
            margin-bottom: 16px;
            color: var(--upwork-slate);
            background: #ffffff;
            outline: none;
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--upwork-green);
            box-shadow: 0 0 0 4px rgba(20, 168, 0, 0.11);
        }

        label {
            color: var(--upwork-slate);
            font-weight: 800;
            margin-bottom: 8px;
        }

        input[type="date"].form-input,
        input[type="time"].form-input,
        input[type="datetime-local"].form-input,
        input[type="month"].form-input,
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        input[type="month"],
        input[type="date"].form-control,
        input[type="time"].form-control,
        input[type="datetime-local"].form-control,
        input[type="month"].form-control {
            color-scheme: light;
            color: var(--upwork-slate);
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.01em;
            direction: ltr;
            text-align: center;
            unicode-bidi: plaintext;
            caret-color: var(--upwork-green-dark);
            -webkit-text-fill-color: var(--upwork-slate);
        }

        input[type="date"].form-input::-webkit-datetime-edit,
        input[type="time"].form-input::-webkit-datetime-edit,
        input[type="datetime-local"].form-input::-webkit-datetime-edit,
        input[type="month"].form-input::-webkit-datetime-edit,
        input[type="date"].form-control::-webkit-datetime-edit,
        input[type="time"].form-control::-webkit-datetime-edit,
        input[type="datetime-local"].form-control::-webkit-datetime-edit,
        input[type="month"].form-control::-webkit-datetime-edit,
        input[type="date"].form-input::-webkit-datetime-edit-fields-wrapper,
        input[type="time"].form-input::-webkit-datetime-edit-fields-wrapper,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-fields-wrapper,
        input[type="month"].form-input::-webkit-datetime-edit-fields-wrapper,
        input[type="date"].form-control::-webkit-datetime-edit-fields-wrapper,
        input[type="time"].form-control::-webkit-datetime-edit-fields-wrapper,
        input[type="datetime-local"].form-control::-webkit-datetime-edit-fields-wrapper,
        input[type="month"].form-control::-webkit-datetime-edit-fields-wrapper {
            color: var(--upwork-slate);
            -webkit-text-fill-color: var(--upwork-slate);
        }

        input[type="date"].form-input::-webkit-datetime-edit-year-field,
        input[type="date"].form-input::-webkit-datetime-edit-month-field,
        input[type="date"].form-input::-webkit-datetime-edit-day-field,
        input[type="time"].form-input::-webkit-datetime-edit-hour-field,
        input[type="time"].form-input::-webkit-datetime-edit-minute-field,
        input[type="time"].form-input::-webkit-datetime-edit-second-field,
        input[type="time"].form-input::-webkit-datetime-edit-millisecond-field,
        input[type="time"].form-input::-webkit-datetime-edit-ampm-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-year-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-month-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-day-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-hour-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-minute-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-second-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-millisecond-field,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-ampm-field,
        input[type="month"].form-input::-webkit-datetime-edit-year-field,
        input[type="month"].form-input::-webkit-datetime-edit-month-field {
            color: var(--upwork-slate);
            -webkit-text-fill-color: var(--upwork-slate);
            font-weight: 800;
        }

        input[type="date"].form-input::-webkit-datetime-edit-text,
        input[type="time"].form-input::-webkit-datetime-edit-text,
        input[type="datetime-local"].form-input::-webkit-datetime-edit-text,
        input[type="month"].form-input::-webkit-datetime-edit-text {
            color: var(--upwork-muted);
            -webkit-text-fill-color: var(--upwork-muted);
            padding: 0 3px;
            font-weight: 700;
        }

        input[type="date"].form-input::-webkit-calendar-picker-indicator,
        input[type="time"].form-input::-webkit-calendar-picker-indicator,
        input[type="datetime-local"].form-input::-webkit-calendar-picker-indicator,
        input[type="month"].form-input::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.9;
            filter: invert(31%) sepia(72%) saturate(1835%) hue-rotate(98deg) brightness(84%) contrast(101%);
        }

        .table-responsive {
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px 16px;
            text-align: right;
            border-bottom: 1px solid var(--upwork-border);
            vertical-align: middle;
        }
        
        th {
            font-weight: 900;
            color: var(--upwork-muted);
            font-size: 14px;
            background: var(--admin-surface-soft);
            white-space: nowrap;
        }

        td {
            font-size: 15px;
        }
        
        /* Grid utilities */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .grid-4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 24px;
        }

        .admin-stat-card {
            padding: 22px;
            min-height: 150px;
        }

        .admin-stat-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }

        .admin-stat-label {
            color: var(--upwork-muted);
            font-size: 14px;
            font-weight: 900;
            line-height: 1.5;
        }

        .admin-stat-value {
            font-size: clamp(28px, 3.5vw, 38px);
            line-height: 1.05;
            font-weight: 900;
            color: var(--upwork-slate);
            direction: ltr;
            text-align: right;
        }

        .admin-stat-icon {
            color: var(--upwork-green-dark);
            background-color: var(--upwork-green-soft);
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex: 0 0 auto;
        }

        .empty-state {
            padding: 34px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--upwork-muted);
            background: var(--admin-surface-soft);
            border: 1px dashed var(--upwork-input-border);
            border-radius: var(--radius-lg);
        }

        .empty-state i {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            color: var(--upwork-green-dark);
            background: #e6f8e6;
            font-size: 22px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .grid-2, .grid-4 {
                grid-template-columns: 1fr;
            }

            .nav-container {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav-user {
                width: 100%;
                align-items: stretch;
                flex-direction: column;
            }

            .admin-nav-links {
                width: 100%;
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .admin-nav-link {
                justify-content: center;
                font-size: 14px;
            }

            .user-dropdown {
                justify-content: space-between;
            }

            main.app-main {
                padding-inline: 14px;
            }

            .card {
                border-radius: 18px;
            }

            th, td {
                padding: 12px;
            }
        }

        @media (max-width: 520px) {
            .admin-nav-links {
                grid-template-columns: 1fr;
            }

            .brand-logo {
                font-size: 20px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <header class="top-nav">
        <div class="nav-container">
            <a href="#" class="brand-logo">
                <span class="logo-mark">أ</span>
                <span>إدارة أنيس</span>
            </a>

            @auth('admin')
                <div class="nav-user">
                    <nav class="admin-nav-links" aria-label="Admin navigation">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active-nav' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> لوحة القيادة
                        </a>
                        <a href="{{ route('admin.workspaces.index') }}" class="admin-nav-link {{ request()->routeIs('admin.workspaces.*') ? 'active-nav' : '' }}">
                            <i class="fa-solid fa-building"></i> مساحات العمل
                        </a>
                        <a href="{{ route('admin.plancodes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.plancodes.*') ? 'active-nav' : '' }}">
                            <i class="fa-solid fa-ticket"></i> أكواد الباقات
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.notifications.*') ? 'active-nav' : '' }}">
                            <i class="fa-solid fa-bell"></i> الإشعارات
                        </a>
                    </nav>

                    <div class="user-dropdown">
                        <div class="user-avatar">
                            {{ mb_substr(Auth::guard('admin')->user()->full_name, 0, 1, 'utf-8') }}
                        </div>
                        <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>خروج</span>
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

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-xmark"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <div class="mb-4">
            <h1 class="page-title">@yield('header')</h1>
        </div>

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
