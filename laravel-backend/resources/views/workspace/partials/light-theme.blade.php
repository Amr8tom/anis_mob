<style>
    /* ════════════════════════════════════════════════════════════
       Workspace portal — light theme overrides
       Overrides dark page variables when data-theme="light"
    ════════════════════════════════════════════════════════════ */
    html[data-theme="light"] {
        --upwork-green:       #14a800;
        --upwork-green-dark:  #0c7a00;
        --upwork-green-soft:  #eef9ee;
        --upwork-slate:       #0d2416;
        --upwork-muted:       #5f705f;
        --upwork-bg:          #f4f7f3;
        --upwork-card-bg:     #ffffff;
        --upwork-border:      #dfe9df;
        --upwork-input-border:#cfdccf;
        --upwork-error:       #d92d20;
        --upwork-error-bg:    #fff1f0;
        --upwork-success:     #14a800;
        --upwork-success-bg:  #effbef;
        --upwork-blue:        #2563eb;
        --portal-surface:     #ffffff;
        --portal-surface-2:   #f7faf6;
        --portal-surface-3:   #ffffff;
        --portal-row-hover:   #eef9ee;
        --portal-shadow:      0 12px 32px rgba(16, 40, 16, .08);
    }

    html[data-theme="light"] body {
        background:
            radial-gradient(circle at top left, rgba(20, 168, 0, .07), transparent 28%),
            linear-gradient(180deg, #f7faf6 0%, #f2f6f1 100%);
    }

    html[data-theme="light"] .app-sidebar,
    html[data-theme="light"] .mobile-topbar,
    html[data-theme="light"] .bottom-nav,
    html[data-theme="light"] .more-sheet {
        background: rgba(255, 255, 255, .95);
        border-color: var(--upwork-border);
    }

    html[data-theme="light"] .card,
    html[data-theme="light"] .manage-form-panel,
    html[data-theme="light"] .uw-page__link {
        background: var(--portal-surface);
        border-color: var(--upwork-border);
        box-shadow: var(--portal-shadow);
    }

    html[data-theme="light"] .form-control,
    html[data-theme="light"] .form-input,
    html[data-theme="light"] .form-select,
    html[data-theme="light"] .lang-select,
    html[data-theme="light"] .theme-toggle-btn,
    html[data-theme="light"] .btn-icon {
        background: var(--portal-surface-3);
        border-color: var(--upwork-input-border);
        color: var(--upwork-slate);
    }

    html[data-theme="light"] input[type="date"],
    html[data-theme="light"] input[type="time"],
    html[data-theme="light"] input[type="datetime-local"],
    html[data-theme="light"] input[type="month"] {
        color-scheme: light;
        color: var(--upwork-slate);
        background: #ffffff;
        -webkit-text-fill-color: var(--upwork-slate);
    }

    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit,
    html[data-theme="light"] input[type="month"]::-webkit-datetime-edit,
    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit-fields-wrapper,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-fields-wrapper,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-fields-wrapper,
    html[data-theme="light"] input[type="month"]::-webkit-datetime-edit-fields-wrapper {
        color: var(--upwork-slate);
        -webkit-text-fill-color: var(--upwork-slate);
    }

    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit-year-field,
    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit-month-field,
    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit-day-field,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-hour-field,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-minute-field,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-second-field,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-millisecond-field,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-ampm-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-year-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-month-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-day-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-hour-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-minute-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-second-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-millisecond-field,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-ampm-field,
    html[data-theme="light"] input[type="month"]::-webkit-datetime-edit-year-field,
    html[data-theme="light"] input[type="month"]::-webkit-datetime-edit-month-field {
        color: var(--upwork-slate);
        -webkit-text-fill-color: var(--upwork-slate);
    }

    html[data-theme="light"] input[type="date"]::-webkit-datetime-edit-text,
    html[data-theme="light"] input[type="time"]::-webkit-datetime-edit-text,
    html[data-theme="light"] input[type="datetime-local"]::-webkit-datetime-edit-text,
    html[data-theme="light"] input[type="month"]::-webkit-datetime-edit-text {
        color: var(--upwork-muted);
        -webkit-text-fill-color: var(--upwork-muted);
    }

    html[data-theme="light"] tbody tr:hover {
        background: var(--portal-row-hover);
    }

    /* 1. Visits & General Dark Theme (--vd-*) */
    html[data-theme="light"] .visits-dark {
        --vd-surface:    var(--upwork-card-bg);
        --vd-surface-2:  var(--upwork-bg);
        --vd-row:        var(--upwork-card-bg);
        --vd-row-alt:    var(--upwork-bg);
        --vd-row-hover:  var(--upwork-green-soft);
        --vd-border:     var(--upwork-border);
        --vd-text:       var(--upwork-slate);
        --vd-text-muted: var(--upwork-muted);
        --vd-text-dim:   #8a978a;
        --vd-accent:     var(--upwork-green);
        --vd-danger:     var(--upwork-error);
        --vd-input:      #ffffff;
    }

    /* 2. Workspace Subscriptions & Clients (--wd-*) */
    html[data-theme="light"] .workspace-dark-page {
        --wd-surface:    var(--upwork-card-bg);
        --wd-surface-2:  var(--upwork-bg);
        --wd-input:      #ffffff;
        --wd-border:     var(--upwork-border);
        --wd-text:       var(--upwork-slate);
        --wd-muted:      var(--upwork-muted);
        --wd-dim:        #8a978a;
        --wd-accent:     var(--upwork-green);
    }

    /* 3. Education Management (--edu-*) */
    html[data-theme="light"] .education-page {
        --edu-surface:    var(--upwork-card-bg);
        --edu-surface-2:  var(--upwork-bg);
        --edu-input:      #ffffff;
        --edu-border:     var(--upwork-border);
        --edu-text:       var(--upwork-slate);
        --edu-muted:      var(--upwork-muted);
        --edu-accent:     var(--upwork-green);
    }

    /* 4. Private Sessions (--ps-*) */
    html[data-theme="light"] .private-session-page,
    html[data-theme="light"] .private-session-list-page,
    html[data-theme="light"] .private-session-create-page {
        --ps-surface:        #ffffff;
        --ps-surface-2:      #f3f8f2;
        --ps-input:          #ffffff;
        --ps-border:         #d5e4d5;
        --ps-text:           #0d2416;
        --ps-muted:          #526853;
        --ps-dim:            #7a8d7d;
        --ps-accent:         #0b9b00;
        --ps-hero-title:     #08230f;
        --ps-hero-muted:     #526853;
        --ps-chip-bg:        #eef8ee;
        --ps-chip-text:      #203a27;
        --ps-chip-border:    #c7ddc7;
        --ps-link:           #0a8f00;
        --ps-success-text:   #087a18;
        --ps-success-bg:     #e9faee;
        --ps-success-border: #a8e7b8;
        --ps-info-text:      #1d4ed8;
        --ps-info-bg:        #eff6ff;
        --ps-info-border:    #bfdbfe;
        --ps-danger-text:    #b42318;
        --ps-danger-bg:      #fff1f0;
        --ps-danger-border:  #fecaca;
    }

    /* Common elements that were statically styled in dark mode */
    html[data-theme="light"] .workspace-dark-hero,
    html[data-theme="light"] .education-hero,
    html[data-theme="light"] .private-session-list-hero,
    html[data-theme="light"] .private-session-create-hero,
    html[data-theme="light"] .private-session-hero {
        background:
            radial-gradient(circle at 10% 20%, rgba(20, 168, 0, .07), transparent 28%),
            linear-gradient(135deg, #ffffff 0%, #f7fbf6 100%);
        border: 1px solid var(--ps-border);
        box-shadow: var(--portal-shadow);
    }

    html[data-theme="light"] .workspace-dark-page tbody tr:hover,
    html[data-theme="light"] .private-session-list-page tbody tr:hover,
    html[data-theme="light"] .private-session-page tbody tr:hover {
        background: var(--upwork-green-soft) !important;
    }
</style>
