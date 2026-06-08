<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الشركاء — أنيس</title>
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
            height: 70px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 22px;
            color: var(--upwork-slate);
            text-decoration: none;
        }

        .brand-logo .logo-mark {
            background-color: var(--upwork-green);
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
            gap: 16px;
        }

        .workspace-badge {
            background-color: var(--upwork-green-soft);
            color: var(--upwork-green-dark);
            font-size: 14px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 99px;
            border: 1px solid var(--upwork-border);
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
            background-color: var(--upwork-green);
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
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 24px;
        }

        /* Base Typography & Elements */
        h1, h2, h3, h4 {
            font-weight: 700;
            color: var(--upwork-slate);
        }

        .page-title {
            font-size: 28px;
            margin-bottom: 8px;
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

        /* Grid utilities */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <header class="top-nav">
        <div class="nav-container">
            <a href="{{ route('landing') }}" class="brand-logo">
                <span class="logo-mark">أ</span>
                <span>أنيس شريك</span>
            </a>

            @auth
                <div class="nav-user">
                    @if(Auth::user()->ownedWorkspace)
                        <div style="display: flex; gap: 15px; margin-left: 20px;">
                            <a href="{{ route('workspace.sessions.index') }}" style="color: var(--upwork-slate); text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 5px;" class="{{ request()->routeIs('workspace.sessions.*') ? 'active-nav' : '' }}">
                                <i class="fa-solid fa-chalkboard-user"></i> الجلسات
                            </a>
                            <a href="{{ route('workspace.clients.index') }}" style="color: var(--upwork-slate); text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 5px;" class="{{ request()->routeIs('workspace.clients.*') ? 'active-nav' : '' }}">
                                <i class="fa-solid fa-users-viewfinder"></i> الزوار
                            </a>
                            <a href="{{ route('workspace.settings.edit') }}" style="color: var(--upwork-slate); text-decoration: none; font-weight: 600; font-size: 15px; display: flex; align-items: center; gap: 5px;" class="{{ request()->routeIs('workspace.settings*') ? 'active-nav' : '' }}">
                                <i class="fa-solid fa-gear"></i> الإعدادات
                            </a>
                        </div>
                        <span class="workspace-badge">
                            <i class="fa-solid fa-store"></i>
                            {{ Auth::user()->ownedWorkspace->name }}
                        </span>
                    @endif

                    <div class="user-dropdown">
                        <div class="user-avatar">
                            {{ mb_substr(Auth::user()->full_name, 0, 1, 'utf-8') }}
                        </div>
                        <form action="{{ route('workspace.logout') }}" method="POST" style="display: inline;">
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

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
