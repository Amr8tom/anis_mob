<style>
    /* ════════════════════════════════════════════════════════════
       Workspace portal — shared dark theme
       One palette, scoped to .visits-dark. No ad-hoc hex values.
    ════════════════════════════════════════════════════════════ */
    .visits-dark {
        --vd-surface:    #16211b;   /* card body                         */
        --vd-surface-2:  #1d2c24;   /* header / footer / thead / panels  */
        --vd-row:        #16211b;   /* default row                       */
        --vd-row-alt:    #1a261f;   /* zebra / reservation row           */
        --vd-row-hover:  #21322a;   /* row hover                         */
        --vd-border:     #2c3d33;   /* all borders / dividers            */
        --vd-text:       #eef3f0;   /* primary text                      */
        --vd-text-muted: #9db0a4;   /* secondary                         */
        --vd-text-dim:   #6c7e73;   /* dim / placeholder / dashes        */
        --vd-accent:     #2bd968;   /* money / positive highlight        */
        --vd-danger:     #ef5350;   /* destructive                       */
        --vd-input:      #0f1813;   /* form inputs                       */
    }

    /* Page heading on dark pages */
    .visits-dark .page-title    { color: var(--vd-text); }
    .visits-dark .page-subtitle { color: var(--vd-text-muted); }

    /* Cards */
    .visits-dark .vd-card {
        background: var(--vd-surface);
        color: var(--vd-text);
        border: 1px solid var(--vd-border);
        border-radius: var(--radius-md);
        box-shadow: 0 10px 30px rgba(0,0,0,0.28);
        overflow: hidden;
        margin-bottom: 22px;
    }
    .visits-dark .vd-card--accent { border-top: 3px solid var(--vd-accent); }
    .visits-dark .vd-card--pad { padding: 22px; }

    /* Section header strip */
    .visits-dark .vd-header {
        display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;
        padding: 18px 26px; background: var(--vd-surface-2); border-bottom: 1px solid var(--vd-border);
    }
    .visits-dark .vd-header h3 { margin:0 0 2px; color: var(--vd-text); }
    .visits-dark .vd-header p  { margin:0; color: var(--vd-text-muted); font-size:13px; }
    .visits-dark .vd-icon {
        width:42px; height:42px; border-radius:12px; flex-shrink:0;
        display:grid; place-items:center; color:#fff; font-size:18px;
        background: rgba(43,217,104,0.15); border:1px solid rgba(43,217,104,0.3);
    }
    .visits-dark .vd-icon i { color: var(--vd-accent); }
    .visits-dark .vd-body { padding: 22px 26px; }

    /* Form panels */
    .visits-dark .manage-form-panel {
        background: var(--vd-surface-2); border: 1px solid var(--vd-border); color: var(--vd-text);
    }
    .visits-dark .manage-form-panel h4 { color: var(--vd-text); }
    .visits-dark label { color: var(--vd-text-muted); }
    .visits-dark .form-control {
        background: var(--vd-input); color: var(--vd-text); border: 1px solid var(--vd-border);
    }
    .visits-dark .form-control::placeholder { color: var(--vd-text-dim); }
    .visits-dark .form-control:focus {
        border-color: var(--vd-accent); box-shadow: 0 0 0 3px rgba(43,217,104,0.15);
    }
    .visits-dark .empty-state {
        color: var(--vd-text-muted); border: 1px dashed var(--vd-border); background: var(--vd-surface-2);
    }
    .visits-dark .empty-state i { color: var(--vd-text-dim); }
    /* Inline soft panel (e.g. recurrence box) */
    .visits-dark .vd-softbox {
        background: var(--vd-input); border: 1px solid var(--vd-border); border-radius: var(--radius-sm);
    }
    .visits-dark .vd-chip {
        border:1px solid var(--vd-border); border-radius:20px; color: var(--vd-text-muted);
    }

    /* Tables */
    .visits-dark table { width:100%; border-collapse:collapse; text-align:right; }
    .visits-dark thead tr { background: var(--vd-surface-2); border-bottom: 1px solid var(--vd-border); }
    .visits-dark th {
        padding:13px 14px; font-size:12px; font-weight:800; color: var(--vd-text-muted);
        white-space:nowrap; letter-spacing:0.3px;
    }
    .visits-dark tbody tr { background: var(--vd-row); border-bottom: 1px solid var(--vd-border); transition: background .15s ease; }
    .visits-dark tbody tr:hover { background: var(--vd-row-hover); }
    .visits-dark tbody tr.vd-row-alt { background: var(--vd-row-alt); }
    .visits-dark td { padding:13px 14px; font-size:14px; color: var(--vd-text); }
    .visits-dark td .sub  { color: var(--vd-text-muted); font-weight:400; }
    .visits-dark td .time { color: var(--vd-text-muted); }
    .visits-dark td .dim  { color: var(--vd-text-dim); }
    .visits-dark td .money { font-weight:800; color: var(--vd-accent); font-size:15px; }
    .visits-dark a.vd-link { color: #6fc3ff; text-decoration: none; font-weight:700; }
    .visits-dark tfoot tr {
        background: var(--vd-surface-2); border-top: 2px solid var(--vd-border);
    }
    .visits-dark tfoot td { padding:16px 14px; font-weight:900; color: var(--vd-text); }
    .visits-dark tfoot td.money { color: var(--vd-accent); font-size:16px; }

    /* Status + badges — consistent translucent style */
    .visits-dark .badge { padding:4px 11px; border-radius:20px; font-size:12px; font-weight:700; white-space:nowrap; }
    .visits-dark .badge-free  { background: rgba(43,217,104,0.16);  color:#4ade80; }
    .visits-dark .badge-global{ background: rgba(59,130,246,0.16);  color:#93c5fd; }
    .visits-dark .badge-space { background: rgba(245,158,11,0.16);  color:#fcd34d; }
    .visits-dark .badge-room  { background: rgba(139,92,246,0.18);  color:#c4b5fd; }
    .visits-dark .badge-on    { background: rgba(43,217,104,0.16);  color:#4ade80; }
    .visits-dark .badge-off   { background: rgba(255,255,255,0.08); color: var(--vd-text-muted); }

    /* Buttons inside dark cards */
    .visits-dark .vd-btn {
        padding:8px 14px; border-radius:var(--radius-sm); border:1px solid var(--vd-border);
        color: var(--vd-text-muted); background: transparent; cursor:pointer;
        font-weight:700; font-size:13px; font-family:inherit; transition: all .15s ease;
    }
    .visits-dark .vd-btn:hover { background: var(--vd-row-hover); color: var(--vd-text); }
    .visits-dark .vd-btn-danger { border-color: rgba(239,83,80,0.5); color: var(--vd-danger); }
    .visits-dark .vd-btn-danger:hover { background: rgba(239,83,80,0.12); color: #ff7b78; }
    .visits-dark .vd-clear-link {
        display:block; margin-top:10px; text-align:center; padding:10px;
        border:1px solid var(--vd-border); border-radius:var(--radius-sm);
        text-decoration:none; color: var(--vd-text-muted); font-weight:700; font-size:14px;
    }
    .visits-dark .vd-clear-link:hover { background: var(--vd-row-hover); color: var(--vd-text); }

    /* ── Mobile tuning ─────────────────────────────────────────── */
    @media (max-width: 600px) {
        .visits-dark .vd-header { padding: 14px 16px; }
        .visits-dark .vd-header h3 { font-size: 17px; }
        .visits-dark .vd-header p  { font-size: 12px; }
        .visits-dark .vd-icon { width: 36px; height: 36px; font-size: 15px; border-radius: 10px; }
        .visits-dark .vd-body { padding: 16px; }
        .visits-dark .vd-card--pad { padding: 16px; }

        /* The header action button drops to its own full-width line. */
        .visits-dark .vd-header > .btn-primary,
        .visits-dark .vd-header > a.btn-primary { width: 100%; justify-content: center; }

        /* Tighter, readable tables on small screens. */
        .visits-dark th { padding: 10px 10px; font-size: 11px; }
        .visits-dark td { padding: 10px 10px; font-size: 13px; }
        .visits-dark tfoot td { padding: 12px 10px; }

        /* Action button groups wrap and stay tappable. */
        .visits-dark .vd-btn { padding: 9px 12px; }
    }
</style>
