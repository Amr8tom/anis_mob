<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $currentDirection ?? 'rtl' }}" data-theme="{{ $currentTheme ?? 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#14a800">
    <title>@yield('title', __('portal.layout.default_title'))</title>
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
        html {
            -webkit-text-size-adjust: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--upwork-bg);
            color: var(--upwork-slate);
            line-height: 1.6;
            min-height: 100vh;
            max-width: 100%;
            overflow-x: hidden;
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
        .btn-icon {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--upwork-bg); border: 1px solid var(--upwork-border);
            color: var(--upwork-slate); font-family: inherit; font-size: 16px;
            width: 38px; height: 38px; border-radius: var(--radius-sm);
            cursor: pointer; transition: var(--transition); flex-shrink: 0;
        }
        .btn-icon:hover { border-color: var(--upwork-green); color: var(--upwork-green-dark); background: var(--upwork-green-soft); }
        .btn-icon.is-danger:hover { border-color: var(--upwork-error); color: var(--upwork-error); background: #fff5f5; }

        .footer-utils {
            display: flex; gap: 8px; margin-top: 14px;
        }
        .footer-utils > form { margin: 0; }
        .lang-select-wrapper { flex: 1; min-width: 0; }
        .lang-select {
            width: 100%; border: 1px solid var(--upwork-border); border-radius: var(--radius-sm);
            padding: 0 12px; height: 38px; font-family: inherit; font-size: 13.5px;
            font-weight: 700; color: var(--upwork-slate); background: var(--upwork-bg); cursor: pointer;
            outline: none; transition: var(--transition);
        }
        .lang-select:focus { border-color: var(--upwork-green); box-shadow: 0 0 0 3px rgba(20,168,0,0.12); }

        .theme-toggle-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; height: 38px; border: 1px solid var(--upwork-border);
            border-radius: var(--radius-sm); background: var(--upwork-bg);
            color: var(--upwork-slate); font-family: inherit; font-size: 13px; font-weight: 800;
            cursor: pointer; transition: var(--transition); white-space: nowrap;
        }
        .theme-toggle-btn:hover { border-color: var(--upwork-green); color: var(--upwork-green-dark); background: var(--upwork-green-soft); }

        /* ═══════════════════════════════════════════
           MAIN  (full remaining width)
        ═══════════════════════════════════════════ */
        .app-main {
            margin-right: var(--sidebar-w);
            min-height: 100vh;
            padding: 36px 40px;
        }
        .content-wrap { width: 100%; max-width: 1480px; min-width: 0; }

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
            min-width: 0;
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
        .grid-2 > *, .grid-3 > *, .grid-4 > * { min-width: 0; }

        /* "Manage" block: list/table = wide primary column, add-form = side panel.
           RTL: the 1fr (main/table) sits on the right, the form on the left. */
        .manage-grid { display: grid; grid-template-columns: 1fr 340px; gap: 22px; align-items: start; }
        .manage-grid > * { min-width: 0; }
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
            min-width: 0;
        }
        textarea.form-control, textarea.form-input { max-width: 100%; }
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
            .app-main { margin-right: 0; padding: 18px 14px calc(var(--bottom-nav-h) + env(safe-area-inset-bottom) + 30px); }
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

            .form-control, .form-input, .form-select {
                min-height: 46px;
                font-size: 16px;
                margin-bottom: 14px;
            }

            .btn-primary,
            .btn-submit,
            .manage-form-panel .btn-primary,
            .manage-form-panel .btn-submit {
                min-height: 46px;
            }

            .manage-form-panel {
                padding: 16px;
            }

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

        @media (max-width: 640px) {
            body {
                overflow-x: hidden;
            }

            .app-main {
                padding-inline: 12px;
            }

            .mobile-topbar {
                padding-inline: 12px;
            }

            .card {
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }

            .page-title,
            h1 {
                font-size: 24px !important;
                line-height: 1.25;
            }

            h2 {
                font-size: 21px !important;
            }

            h3 {
                font-size: 18px !important;
            }

            .alert {
                padding: 12px 14px;
                align-items: flex-start;
            }

            .table-responsive {
                overflow-x: visible;
            }

            .table-responsive table.mobile-card-table {
                min-width: 0 !important;
                width: 100% !important;
                border-collapse: separate;
                border-spacing: 0 12px;
            }

            .mobile-card-table thead {
                display: none;
            }

            .mobile-card-table tbody,
            .mobile-card-table tfoot,
            .mobile-card-table tr,
            .mobile-card-table td {
                display: block;
                width: 100%;
            }

            .mobile-card-table tbody tr,
            .mobile-card-table tfoot tr {
                border: 1px solid var(--upwork-border) !important;
                border-radius: var(--radius-md);
                padding: 10px 12px;
                margin-bottom: 12px;
                background: var(--upwork-card-bg);
                box-shadow: var(--shadow-xs);
            }

            .workspace-dark-page .mobile-card-table tbody tr,
            .workspace-dark-page .mobile-card-table tfoot tr {
                background: var(--wd-surface, #16211b) !important;
                border-color: var(--wd-border, #2c3d33) !important;
            }
            .visits-dark .mobile-card-table tbody tr,
            .visits-dark .mobile-card-table tfoot tr {
                background: var(--vd-surface, #16211b) !important;
                border-color: var(--vd-border, #2c3d33) !important;
            }
            .private-session-page .mobile-card-table tbody tr,
            .private-session-list-page .mobile-card-table tbody tr {
                background: var(--ps-surface, #16211b) !important;
                border-color: var(--ps-border, #2c3d33) !important;
            }
            .education-page .mobile-card-table tbody tr {
                background: var(--edu-surface, #16211b) !important;
                border-color: var(--edu-border, #2c3d33) !important;
            }

            .mobile-card-table td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 14px;
                padding: 8px 0 !important;
                border-bottom: 1px solid rgba(126, 150, 130, .18) !important;
                text-align: start !important;
                white-space: normal !important;
            }

            .mobile-card-table td:last-child {
                border-bottom: 0 !important;
            }

            .mobile-card-table td::before {
                content: attr(data-label);
                min-width: 94px;
                max-width: 42%;
                color: var(--upwork-muted);
                font-size: 12px;
                font-weight: 900;
                line-height: 1.45;
            }

            .mobile-card-table td[colspan] {
                display: block;
                text-align: center !important;
            }

            .mobile-card-table td[colspan]::before,
            .mobile-card-table td[data-label=""]::before {
                display: none;
            }

            .mobile-card-table td form,
            .mobile-card-table td .btn-primary,
            .mobile-card-table td button,
            .mobile-card-table td a {
                max-width: 100%;
            }

            .mobile-card-table td .btn-primary,
            .mobile-card-table td button {
                width: 100%;
                justify-content: center;
            }

            .uw-pagination__list {
                justify-content: center;
            }

            .uw-page__link {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
            }

            .private-session-hero,
            .private-session-list-hero,
            .education-hero,
            .vd-header {
                padding: 16px !important;
                gap: 14px !important;
                align-items: stretch !important;
            }

            .private-session-actions,
            .vd-header,
            .education-card-head {
                flex-direction: column !important;
            }

            .private-session-actions > *,
            .private-session-action-main,
            .session-action-wrap,
            .session-action-btn,
            .private-session-create-btn,
            .vd-header form,
            .vd-header button {
                width: 100%;
                justify-content: center;
            }

            .private-session-filter-grid,
            .education-grid,
            .education-inline-form {
                grid-template-columns: 1fr !important;
            }

            .private-session-summary-grid {
                grid-template-columns: 1fr !important;
            }

            .workspace-dark-stat h2,
            .private-session-stat h2 {
                font-size: 24px !important;
            }

            [style*="grid-template-columns: repeat(4, 1fr)"],
            [style*="grid-template-columns:repeat(4,1fr)"],
            [style*="grid-template-columns: repeat(3, 1fr)"],
            [style*="grid-template-columns:repeat(3,1fr)"],
            [style*="grid-template-columns: repeat(2, 1fr)"],
            [style*="grid-template-columns:repeat(2,1fr)"] {
                grid-template-columns: 1fr !important;
            }
        }

        /* Guest pages (login/register/pending) have no sidebar */
        body.guest .app-main { margin-right: 0; }
        body.guest .content-wrap { max-width: 560px; margin: 0 auto; }

        /* Workspace-wide responsive guardrails.
           These protect every owner-portal tab from half-visible forms caused by
           wide inline grids/flex rows, sticky side panels, or long table content. */
        .content-wrap *,
        .content-wrap *::before,
        .content-wrap *::after {
            max-width: 100%;
        }

        .content-wrap [style*="display:flex"],
        .content-wrap [style*="display: flex"],
        .content-wrap [style*="display:grid"],
        .content-wrap [style*="display: grid"] {
            min-width: 0;
        }

        .content-wrap [style*="display:flex"] > *,
        .content-wrap [style*="display: flex"] > *,
        .content-wrap [style*="display:grid"] > *,
        .content-wrap [style*="display: grid"] > * {
            min-width: 0;
        }

        .content-wrap input,
        .content-wrap select,
        .content-wrap textarea,
        .content-wrap button {
            max-width: 100%;
        }

        .content-wrap img,
        .content-wrap video,
        .content-wrap canvas {
            max-width: 100%;
        }

        @media (max-width: 1100px) {
            .content-wrap [style*="position: sticky"],
            .content-wrap [style*="position:sticky"],
            .manage-aside {
                position: static !important;
                top: auto !important;
            }

            .notif-grid,
            .manage-grid,
            .private-session-filter-grid,
            .education-grid,
            .education-inline-form {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 640px) {
            .content-wrap [style*="display:flex"],
            .content-wrap [style*="display: flex"] {
                flex-wrap: wrap !important;
            }

            .content-wrap [style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }

            .settings-container,
            .auth-card,
            .notif-grid,
            .manage-main,
            .manage-aside {
                width: 100% !important;
                max-width: 100% !important;
            }

            .content-wrap .btn-primary,
            .content-wrap .btn-submit,
            .content-wrap button[type="submit"] {
                max-width: 100%;
            }
        }
    </style>
    @include('workspace.partials.light-theme')
    @yield('styles')
</head>
<body class="{{ Auth::guard('workspace_owner')->guest() ? 'guest' : '' }}">

@php
    $workspaceOwner = Auth::guard('workspace_owner')->user();
    $navItems = [
        ['route' => 'workspace.sessions.index',      'match' => 'workspace.sessions.*',      'icon' => 'fa-chalkboard-user',  'label' => __('portal.nav.sessions')],
        ['route' => 'workspace.education.index',     'match' => 'workspace.education.*',     'icon' => 'fa-graduation-cap',   'label' => __('portal.nav.education')],
        ['route' => 'workspace.private-sessions.index', 'match' => 'workspace.private-sessions.*', 'icon' => 'fa-qrcode', 'label' => __('portal.nav.private_sessions')],
        ['route' => 'workspace.clients.index',        'match' => 'workspace.clients.*',       'icon' => 'fa-users-viewfinder', 'label' => __('portal.nav.clients')],
        ['route' => 'workspace.visits.index',         'match' => 'workspace.visits.*',        'icon' => 'fa-right-to-bracket', 'label' => __('portal.nav.visits')],
        ['route' => 'workspace.subscriptions.index',  'match' => 'workspace.subscriptions.*', 'icon' => 'fa-ticket',           'label' => __('portal.nav.subscriptions')],
        ['route' => 'workspace.rooms.index',          'match' => 'workspace.rooms.*',         'icon' => 'fa-door-open',        'label' => __('portal.nav.rooms')],
        ['route' => 'workspace.notifications.index',  'match' => 'workspace.notifications.*', 'icon' => 'fa-bell',             'label' => __('portal.nav.notifications')],
        ['route' => 'workspace.settings.edit',        'match' => 'workspace.settings*',       'icon' => 'fa-gear',             'label' => __('portal.nav.settings')],
    ];
    $bottomPrimaryRoutes = [
        'workspace.visits.index',
        'workspace.clients.index',
        'workspace.private-sessions.index',
        'workspace.subscriptions.index',
    ];
    $bottomPrimary = collect($bottomPrimaryRoutes)
        ->map(fn ($route) => collect($navItems)->firstWhere('route', $route))
        ->filter()
        ->values()
        ->all();
    $bottomMore = collect($navItems)
        ->reject(fn ($item) => in_array($item['route'], $bottomPrimaryRoutes, true))
        ->values()
        ->all();
    $hasWorkspace  = $workspaceOwner !== null && $workspaceOwner->ownedWorkspace;
@endphp

{{-- ═══ DESKTOP SIDEBAR ═══ --}}
@if($hasWorkspace)
<aside class="app-sidebar">
    <a href="{{ route('landing') }}" class="sb-brand">
        <span class="logo-mark">{{ __('portal.layout.brand_mark') }}</span>
        <span>{{ __('portal.layout.brand') }}</span>
    </a>

    <nav class="sb-nav">
        <div class="sb-section-label">{{ __('portal.layout.menu') }}</div>
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
            <span class="ws-name">{{ $workspaceOwner->ownedWorkspace->name }}</span>
        </span>
        <div class="sb-user">
            <div class="user-avatar">{{ mb_substr($workspaceOwner->full_name, 0, 1, 'utf-8') }}</div>
            <div class="u-meta"><div class="u-name">{{ $workspaceOwner->full_name }}</div></div>
            <form action="{{ route('workspace.logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-icon is-danger" title="{{ __('portal.nav.logout') }}" aria-label="{{ __('portal.nav.logout') }}">
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 16px; height: 16px; transform: scaleX(-1); fill: currentColor;"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32L64 128c0-17.7 14.3-32 32-32l64 0z"/></svg>
                </button>
            </form>
        </div>
        <div class="footer-utils">
            <form action="{{ route('locale.switch') }}" method="POST" id="locale-switch-form" class="lang-select-wrapper">
                @csrf
                <select name="locale" class="lang-select" onchange="document.getElementById('locale-switch-form').submit()" title="{{ __('portal.language') }}" aria-label="{{ __('portal.language') }}">
                    @foreach(config('locales.supported') as $code => $meta)
                        <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $meta['native'] }}</option>
                    @endforeach
                </select>
            </form>
            <form action="{{ route('theme.switch') }}" method="POST" style="flex:1; min-width:0;">
                @csrf
                <input type="hidden" name="theme" value="{{ ($currentTheme ?? 'dark') === 'dark' ? 'light' : 'dark' }}">
                <button type="submit" class="theme-toggle-btn" title="{{ __('portal.theme.toggle') }}">
                    @if(($currentTheme ?? 'dark') === 'dark')
                        <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 15px; height: 15px; fill: currentColor;"><path d="M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L446.9 256l62.3 90.3c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 446.9l-90.3 62.3c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L65.1 256 2.8 165.7c-3.1-4.5-3.7-10.2-1.6-15.2s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 65.1l90.3-62.3c4.5-3.1 10.2-3.7 15.2-1.6zM256 160a96 96 0 1 0 0 192 96 96 0 1 0 0-192z"></path></svg>
                    @else
                        <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" style="width: 14px; height: 14px; fill: currentColor;"><path d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg>
                    @endif
                    <span>{{ ($currentTheme ?? 'dark') === 'dark' ? __('portal.theme.light') : __('portal.theme.dark') }}</span>
                </button>
            </form>
        </div>
    </div>
</aside>
@endif

{{-- ═══ MOBILE TOP BAR ═══ --}}
@if($workspaceOwner !== null)
<div class="mobile-topbar">
    @if($hasWorkspace)
        <span class="mt-ws"><span class="ws-dot"></span><span class="ws-name">{{ $workspaceOwner->ownedWorkspace->name }}</span></span>
    @else
        <a href="{{ route('landing') }}" class="sb-brand" style="border:none;padding:0;font-size:17px;"><span class="logo-mark" style="width:32px;height:32px;font-size:16px;">{{ __('portal.layout.brand_mark') }}</span> {{ __('portal.layout.brand') }}</a>
    @endif
    <div class="user-avatar" style="width:32px;height:32px;font-size:13px;">{{ mb_substr($workspaceOwner->full_name, 0, 1, 'utf-8') }}</div>
</div>
@endif

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
        <span>{{ __('portal.layout.more') }}</span>
    </button>
</nav>

<div class="more-sheet-overlay" id="moreSheet" onclick="if(event.target===this) toggleMoreSheet(false)">
    <div class="more-sheet" role="dialog" aria-modal="true" aria-label="{{ __('portal.layout.more_menu') }}">
        <div class="grip"></div>
        <div class="ms-title">{{ __('portal.layout.more_options') }}</div>
        @foreach($bottomMore as $item)
            <a href="{{ route($item['route']) }}" class="ms-item {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
        <form action="{{ route('theme.switch') }}" method="POST" style="margin:0;">
            @csrf
            <input type="hidden" name="theme" value="{{ ($currentTheme ?? 'dark') === 'dark' ? 'light' : 'dark' }}">
            <button type="submit" class="ms-item" style="width:100%; background:none; border:none; text-align:start; cursor:pointer; font-family:inherit;">
                @if(($currentTheme ?? 'dark') === 'dark')
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 18px; height: 18px; margin-inline-end: 10px; fill: currentColor;"><path d="M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L446.9 256l62.3 90.3c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 446.9l-90.3 62.3c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L65.1 256 2.8 165.7c-3.1-4.5-3.7-10.2-1.6-15.2s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 65.1l90.3-62.3c4.5-3.1 10.2-3.7 15.2-1.6zM256 160a96 96 0 1 0 0 192 96 96 0 1 0 0-192z"></path></svg>
                @else
                    <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" style="width: 14px; height: 18px; margin-inline-end: 10px; fill: currentColor;"><path d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg>
                @endif
                {{ ($currentTheme ?? 'dark') === 'dark' ? __('portal.theme.light') : __('portal.theme.dark') }}
            </button>
        </form>
        <div class="ms-divider"></div>
        <form action="{{ route('workspace.logout') }}" method="POST" class="ms-logout-form">
            @csrf
            <button type="submit" class="ms-item danger"><svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="width: 18px; height: 18px; margin-inline-end: 10px; transform: scaleX(-1); fill: currentColor;"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32L64 128c0-17.7 14.3-32 32-32l64 0z"/></svg> {{ __('portal.nav.logout') }}</button>
        </form>
    </div>
</div>

<script>
    function prepareMobileTables(root) {
        root = root || document;
        root.querySelectorAll('.content-wrap table, .table-responsive table').forEach(function (table) {
            if (table.dataset.mobilePrepared === '1') {
                return;
            }

            const headers = Array.from(table.querySelectorAll('thead th')).map(function (th) {
                return th.textContent.trim();
            });

            if (!headers.length) {
                return;
            }

            table.classList.add('mobile-card-table');
            table.querySelectorAll('tbody tr, tfoot tr').forEach(function (row) {
                let columnIndex = 0;
                Array.from(row.children).forEach(function (cell) {
                    if (!cell.hasAttribute('data-label')) {
                        cell.setAttribute('data-label', headers[columnIndex] || '');
                    }
                    columnIndex += Number(cell.getAttribute('colspan') || 1);
                });
            });

            table.dataset.mobilePrepared = '1';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        prepareMobileTables(document);
    });

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
