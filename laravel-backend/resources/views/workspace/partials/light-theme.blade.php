<style>
    /* ════════════════════════════════════════════════════════════
       Workspace portal — light theme overrides
       Overrides dark page variables when data-theme="light"
    ════════════════════════════════════════════════════════════ */

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
    html[data-theme="light"] .private-session-list-page {
        --ps-surface:    var(--upwork-card-bg);
        --ps-surface-2:  var(--upwork-bg);
        --ps-input:      #ffffff;
        --ps-border:     var(--upwork-border);
        --ps-text:       var(--upwork-slate);
        --ps-muted:      var(--upwork-muted);
        --ps-dim:        #8a978a;
        --ps-accent:     var(--upwork-green);
    }

    /* Common elements that were statically styled in dark mode */
    html[data-theme="light"] .workspace-dark-hero,
    html[data-theme="light"] .education-hero,
    html[data-theme="light"] .private-session-list-hero,
    html[data-theme="light"] .private-session-hero {
        background: var(--upwork-card-bg);
        border: 1px solid var(--upwork-border);
        box-shadow: var(--shadow-md);
    }

    html[data-theme="light"] .workspace-dark-page tbody tr:hover,
    html[data-theme="light"] .private-session-list-page tbody tr:hover,
    html[data-theme="light"] .private-session-page tbody tr:hover {
        background: var(--upwork-green-soft) !important;
    }
</style>
