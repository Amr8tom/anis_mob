@extends('workspace.layouts.app')

@section('title', $session->title.' | '.__('portal.private_sessions.meta_suffix'))

@section('content')
<div class="private-session-page">
    <a href="{{ route('workspace.private-sessions.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">{{ __('portal.private_sessions.create.back') }}</a>
    <div class="private-session-hero">
        <div>
            <h1 class="private-session-title">{{ $session->title }}</h1>
            <p class="private-session-subtitle">{{ $session->starts_at->format('Y/m/d H:i') }} · {{ number_format($session->priceEgp(), 2) }} {{ __('portal.private_sessions.per_person') }}</p>
            <div class="private-session-meta-line">
                <span><i class="fa-solid fa-person-chalkboard"></i> {{ $session->centerTeacher?->name ?? $session->host_name ?? __('portal.private_sessions.no_teacher') }}</span>
                <span><i class="fa-solid fa-book-open"></i> {{ $session->centerSubject?->name ?? __('portal.private_sessions.no_subject') }}</span>
                <span><i class="fa-solid fa-layer-group"></i> {{ $session->centerGradeLevel?->name ?? __('portal.private_sessions.no_grade') }}</span>
                <span><i class="fa-solid fa-hand-holding-dollar"></i> {{ __('portal.private_sessions.show.instructor_payout') }} {{ $session->instructorPayoutLabel() }}</span>
            </div>
        </div>
        <div class="private-session-actions">
            <a href="{{ route('workspace.private-sessions.qr', $session) }}" class="btn-primary private-session-action-main" style="text-decoration:none;"><i class="fa-solid fa-qrcode"></i> {{ __('portal.private_sessions.show.qr_btn') }}</a>
            @if($session->status === 'active')
                <form action="{{ route('workspace.private-sessions.finish', $session) }}" method="POST" class="session-action-wrap">
                    @csrf
                    <button class="session-action-btn session-action-finish" type="submit" onclick="return confirm('{{ __('portal.private_sessions.show.finish_confirm') }}');">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ __('portal.private_sessions.show.finish') }}
                    </button>
                    <span class="session-action-tooltip">{{ __('portal.private_sessions.show.finish_tooltip') }}</span>
                </form>
                <form action="{{ route('workspace.private-sessions.cancel', $session) }}" method="POST" class="session-action-wrap" onsubmit="return confirm('{{ __('portal.private_sessions.show.cancel_confirm') }}');">
                    @csrf
                    <button class="session-action-btn session-action-cancel" type="submit">
                        <i class="fa-solid fa-ban"></i>
                        {{ __('portal.private_sessions.show.cancel') }}
                    </button>
                    <span class="session-action-tooltip">{{ __('portal.private_sessions.show.cancel_tooltip') }}</span>
                </form>
            @endif
            <small class="session-action-hint">{{ __('portal.private_sessions.show.action_hint') }}</small>
        </div>
    </div>

    @if(session('import_errors'))
        <div class="card private-session-dark-card" style="margin-bottom:16px; border-right:4px solid #f87171;">
            <strong>{{ __('portal.private_sessions.show.import_notes') }}</strong>
            <ul style="margin:8px 0 0;">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($importBatch && $importRows && $importRows->count() > 0)
        <div class="card private-session-dark-card import-preview-card" style="margin-bottom:22px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
                <div>
                    <h3 style="margin:0 0 6px;">{{ __('portal.private_sessions.show.import_preview') }}</h3>
                    <p style="margin:0; color:var(--ps-muted);">{{ __('portal.private_sessions.show.import_preview_hint', ['file' => $importBatch->original_filename ?? '—']) }}</p>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="import-chip import-chip-valid">{{ __('portal.private_sessions.show.chip_valid', ['count' => $importBatch->valid_count]) }}</span>
                    <span class="import-chip import-chip-duplicate">{{ __('portal.private_sessions.show.chip_duplicate', ['count' => $importBatch->duplicate_count]) }}</span>
                    <span class="import-chip import-chip-failed">{{ __('portal.private_sessions.show.chip_failed', ['count' => $importBatch->failed_count]) }}</span>
                </div>
            </div>
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead>
                        <tr>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_row') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_name') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_phone') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_type') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_status') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.show.th_note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($importRows as $row)
                            @php
                                $status = $row->status ?? 'failed';
                                $statusLabel = __('portal.private_sessions.show.import_status_'.(in_array($status, ['valid','duplicates','failed'], true) ? $status : 'failed'));
                            @endphp
                            <tr>
                                <td style="padding:10px;">{{ $row->row_number ?? '—' }}</td>
                                <td style="padding:10px; font-weight:700;">{{ $row->resolved_name ?? $row->name_snapshot ?? '—' }}</td>
                                <td style="padding:10px; direction:ltr; text-align:right;">{{ $row->phone_snapshot ?? '—' }}</td>
                                <td style="padding:10px;">{{ $row->visitor_type ?? '—' }}</td>
                                <td style="padding:10px;"><span class="import-chip import-chip-{{ $status }}">{{ $statusLabel }}</span></td>
                                <td style="padding:10px; color:var(--ps-muted);">{{ $row->message ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;">{{ $importRows->links('vendor.pagination.upwork') }}</div>
            <form action="{{ route('workspace.private-sessions.attendees.import.confirm', $session) }}" method="POST" style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
                @csrf
                <button type="submit" class="btn-primary" @disabled($importBatch->valid_count < 1)>
                    <i class="fa-solid fa-check"></i> {{ __('portal.private_sessions.show.save_valid') }}
                </button>
                <small style="align-self:center; color:var(--ps-muted);">{{ __('portal.private_sessions.show.save_hint') }}</small>
            </form>
        </div>
    @endif

    <div class="grid-4 private-session-summary-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px;">
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_invited') }}</small><h2 style="margin:8px 0 0;">{{ $summary['invited'] }}</h2></div>
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_attended') }}</small><h2 style="margin:8px 0 0;">{{ $summary['attended'] }}</h2></div>
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_actual_revenue') }}</small><h2 style="margin:8px 0 0;">{{ number_format($summary['actual_revenue_cents'] / 100, 2) }} {{ __('portal.egp') }}</h2></div>
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_center_net') }}</small><h2 style="margin:8px 0 0;">{{ number_format($summary['center_net_cents'] / 100, 2) }} {{ __('portal.egp') }}</h2></div>
    </div>
    <div class="grid-2 private-session-summary-grid" style="gap:12px; margin-bottom:18px;">
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_instructor_payout') }}</small><h2 style="margin:8px 0 0;">{{ number_format($summary['instructor_payout_cents'] / 100, 2) }} {{ __('portal.egp') }}</h2></div>
        <div class="card private-session-stat"><small>{{ __('portal.private_sessions.show.stat_qr_manual') }}</small><h2 style="margin:8px 0 0;">{{ $summary['qr_checkins'] }} / {{ $summary['owner_checkins'] }}</h2></div>
    </div>

    <div class="manage-grid" style="margin-bottom:22px;">
        <div class="manage-main card private-session-dark-card">
            <h3 style="margin:0 0 14px;">{{ __('portal.private_sessions.show.add_manual') }}</h3>
            <form action="{{ route('workspace.private-sessions.attendees.store', $session) }}" method="POST">
                @csrf
                <div class="grid-2" style="gap:12px;">
                    <div>
                        <label>{{ __('portal.private_sessions.show.phone') }}</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" required>
                    </div>
                    <div>
                        <label>{{ __('portal.private_sessions.show.name_if') }}</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                <label style="display:flex; gap:8px; align-items:center; margin-top:12px; font-weight:700;">
                    <input type="checkbox" name="check_in_now" value="1" id="private-session-check-in-now">
                    {{ __('portal.private_sessions.show.check_in_now') }}
                </label>
                <button type="submit" class="btn-primary" style="margin-top:12px;">{{ __('portal.private_sessions.show.add_to_session') }}</button>
            </form>
        </div>
        <aside class="manage-aside card private-session-dark-card">
            <h3 style="margin:0 0 14px;">{{ __('portal.private_sessions.show.import_title') }}</h3>
            <p style="color:var(--upwork-muted); font-size:13px;">{{ __('portal.private_sessions.show.import_hint') }}</p>
            <form action="{{ route('workspace.private-sessions.attendees.import', $session) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="attendees_file" class="form-control" accept=".csv,.xlsx,text/csv,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                <button type="submit" class="btn-primary" style="margin-top:12px;">{{ __('portal.private_sessions.show.import_btn') }}</button>
            </form>
        </aside>
    </div>

    <div class="card private-session-dark-card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 14px;">{{ __('portal.private_sessions.show.attended_title') }}</h3>
        @if($attendedRows->isEmpty())
            <p class="empty-state">{{ __('portal.private_sessions.show.attended_empty') }}</p>
        @else
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead><tr style="border-bottom:2px solid var(--upwork-border);"><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_visitor') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_phone') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_type') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_check_in') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_method') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.att_th_amount') }}</th></tr></thead>
                    <tbody>
                        @foreach($attendedRows as $attendee)
                            <tr style="border-bottom:1px solid var(--upwork-border);">
                                <td style="padding:10px; font-weight:700;">{{ $attendee->name_snapshot }}</td>
                                <td style="padding:10px; direction:ltr; text-align:right;">{{ $attendee->phone_snapshot }}</td>
                                <td style="padding:10px;">{{ $attendee->user_id ? __('portal.private_sessions.show.app_user') : __('portal.private_sessions.show.walk_in') }}</td>
                                <td style="padding:10px;"><small>{{ $attendee->checked_in_at?->format('Y/m/d H:i') }}</small></td>
                                <td style="padding:10px;">{{ $attendee->checked_in_method === 'qr' ? 'QR' : __('portal.private_sessions.show.method_owner') }}</td>
                                <td style="padding:10px; color:var(--upwork-green-dark); font-weight:700;">{{ number_format($attendee->amountEgp(), 2) }} {{ __('portal.egp') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;">{{ $attendedRows->links('vendor.pagination.upwork') }}</div>
        @endif
    </div>

    <div class="card private-session-dark-card">
        <form method="GET" action="{{ route('workspace.private-sessions.show', $session) }}" style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
            <h3 style="margin:0;">{{ __('portal.private_sessions.show.all_registered') }}</h3>
            @if($attendees->total() > 0 || $attendeeSearch !== '')
                <div style="min-width:260px; max-width:420px; flex:1;">
                    <label for="private-session-attendee-search" style="font-size:12px; color:var(--upwork-muted);">{{ __('portal.private_sessions.show.search_label') }}</label>
                    <input id="private-session-attendee-search" name="attendee_search" value="{{ $attendeeSearch }}" type="search" class="form-control" placeholder="{{ __('portal.private_sessions.show.search_placeholder') }}" style="margin-top:4px;">
                </div>
            @endif
        </form>
        @if($attendees->isEmpty())
            <p class="empty-state">{{ __('portal.private_sessions.show.registered_empty') }}</p>
        @else
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead><tr style="border-bottom:2px solid var(--upwork-border);"><th style="padding:10px;">{{ __('portal.private_sessions.show.reg_th_name') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.reg_th_phone') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.reg_th_source') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.reg_th_status') }}</th><th style="padding:10px;">{{ __('portal.private_sessions.show.reg_th_action') }}</th></tr></thead>
                    <tbody id="private-session-attendees-body">
                        @foreach($attendees as $attendee)
                            <tr data-attendee-row data-search="{{ strtolower($attendee->name_snapshot.' '.$attendee->phone_snapshot.' '.$attendee->phone_normalized) }}" style="border-bottom:1px solid var(--upwork-border);">
                                <td style="padding:10px; font-weight:700;">{{ $attendee->name_snapshot }}</td>
                                <td style="padding:10px; direction:ltr; text-align:right;">{{ $attendee->phone_snapshot }}</td>
                                <td style="padding:10px;">{{ $attendee->source }}</td>
                                <td style="padding:10px;">{{ $attendee->status === 'attended' ? __('portal.private_sessions.show.status_attended') : __('portal.private_sessions.show.status_waiting') }}</td>
                                <td style="padding:10px;">
                                    @if($attendee->status !== 'attended')
                                        <form action="{{ route('workspace.private-sessions.attendees.check-in', [$session, $attendee]) }}" method="POST">@csrf<button class="btn-primary" type="submit" style="box-shadow:none; padding:7px 12px;">{{ __('portal.private_sessions.show.check_in') }}</button></form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr id="private-session-attendees-empty-search" style="display:none;">
                            <td colspan="5" class="empty-state">{{ __('portal.private_sessions.show.search_empty') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;">{{ $attendees->links('vendor.pagination.upwork') }}</div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .private-session-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
        margin: 8px 0 18px;
        padding: 22px;
        border-radius: var(--radius-md);
        border: 1px solid #2c3d33;
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .12), transparent 28%),
            linear-gradient(135deg, var(--ps-surface, #16211b) 0%, var(--ps-surface-2, #1d2c24) 100%);
        color: #eef3f0;
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .private-session-page {
        --ps-surface: #16211b;
        --ps-surface-2: #1d2c24;
        --ps-input: #0f1813;
        --ps-border: #2c3d33;
        --ps-text: #eef3f0;
        --ps-muted: #9db0a4;
        --ps-dim: #6c7e73;
        --ps-accent: #2bd968;
    }

    .private-session-page .card,
    .private-session-page .private-session-dark-card,
    .private-session-page .private-session-stat {
        background: var(--ps-surface) !important;
        color: var(--ps-text) !important;
        border: 1px solid var(--ps-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .private-session-page .private-session-dark-card h3,
    .private-session-page .private-session-stat h2 {
        color: var(--ps-text);
    }

    .private-session-page .private-session-stat small,
    .private-session-page .private-session-dark-card p,
    .private-session-page .private-session-dark-card label,
    .private-session-page .private-session-dark-card small {
        color: var(--ps-muted) !important;
    }

    .private-session-page .private-session-stat h2 {
        color: var(--ps-accent);
    }

    .private-session-page .form-control {
        background: var(--ps-input) !important;
        border-color: var(--ps-border) !important;
        color: var(--ps-text) !important;
    }

    .private-session-page .form-control::placeholder {
        color: var(--ps-dim);
    }

    .private-session-page .table-responsive table {
        color: var(--ps-text);
    }

    .private-session-page thead tr {
        background: var(--ps-surface-2) !important;
        border-bottom-color: var(--ps-border) !important;
    }

    .private-session-page th {
        color: var(--ps-muted);
    }

    .private-session-page tbody tr {
        border-bottom-color: var(--ps-border) !important;
    }

    .private-session-page tbody tr:hover {
        background: var(--ps-row-hover, #21322a);
    }

    .private-session-page td {
        color: var(--ps-text) !important;
    }

    .private-session-page td[style*="color:var(--upwork-green-dark)"] {
        color: var(--ps-accent) !important;
    }

    .private-session-page .empty-state {
        color: var(--ps-muted);
        background: transparent;
    }

    .private-session-page .btn-secondary {
        background: var(--ps-input);
        border-color: var(--ps-border);
        color: var(--ps-text);
    }

    .private-session-page .import-preview-card {
        border-top: 3px solid var(--ps-accent) !important;
    }

    .private-session-page .import-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        border: 1px solid var(--ps-border);
        background: var(--ps-input);
        color: var(--ps-muted);
        white-space: nowrap;
    }

    .private-session-page .import-chip-valid {
        color: #7ef7a4;
        background: rgba(43, 217, 104, .12);
        border-color: rgba(43, 217, 104, .34);
    }

    .private-session-page .import-chip-duplicates,
    .private-session-page .import-chip-duplicate {
        color: #fde68a;
        background: rgba(245, 158, 11, .14);
        border-color: rgba(251, 191, 36, .32);
    }

    .private-session-page .import-chip-failed {
        color: #fecaca;
        background: rgba(185, 28, 28, .16);
        border-color: rgba(248, 113, 113, .32);
    }

    .private-session-title {
        margin: 0 0 8px;
        color: #eef3f0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 900;
    }

    .private-session-subtitle {
        margin: 0;
        color: #9db0a4;
        font-weight: 700;
    }

    .private-session-meta-line {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .private-session-meta-line span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        border: 1px solid #2c3d33;
        color: #c9d8cf;
        background: rgba(15, 24, 19, .7);
        font-size: 12px;
        font-weight: 800;
    }

    .private-session-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        max-width: 680px;
    }

    .private-session-action-main {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding-inline: 22px;
    }

    .session-action-btn {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        font-family: inherit;
        font-weight: 800;
        cursor: pointer;
        transition: transform .12s ease, box-shadow .12s ease, background .12s ease;
    }

    .session-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(0, 0, 0, .22);
    }

    .session-action-wrap {
        position: relative;
        margin: 0;
    }

    .session-action-tooltip {
        position: absolute;
        z-index: 20;
        inset-inline-end: 0;
        top: calc(100% + 10px);
        width: min(360px, 80vw);
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        background: #0f1813;
        color: #fff;
        border: 1px solid #2c3d33;
        box-shadow: 0 14px 32px rgba(0, 0, 0, .28);
        font-size: 12px;
        line-height: 1.6;
        text-align: start;
        opacity: 0;
        transform: translateY(-4px);
        pointer-events: none;
        transition: opacity .12s ease, transform .12s ease;
    }

    .session-action-wrap:hover .session-action-tooltip,
    .session-action-wrap:focus-within .session-action-tooltip {
        opacity: 1;
        transform: translateY(0);
    }

    .session-action-finish {
        background: rgba(43, 217, 104, .16);
        color: #7ef7a4;
        border-color: rgba(43, 217, 104, .38);
    }

    .session-action-cancel {
        background: rgba(185, 28, 28, .16);
        color: #fecaca;
        border-color: rgba(248, 113, 113, .32);
    }

    .session-action-hint {
        flex-basis: 100%;
        color: #9db0a4;
        text-align: start;
        font-size: 12px;
        line-height: 1.6;
    }
</style>
@endsection

@section('scripts')
<script>
    (function () {
        const checkInNow = document.getElementById('private-session-check-in-now');
        const checkInKey = 'workspace-private-session:{{ $session->id }}:check-in-now';

        if (checkInNow) {
            checkInNow.checked = localStorage.getItem(checkInKey) === '1';
            checkInNow.addEventListener('change', function () {
                localStorage.setItem(checkInKey, checkInNow.checked ? '1' : '0');
            });
        }

        const search = document.getElementById('private-session-attendee-search');

        if (search) {
            let timer = null;
            const form = search.closest('form');
            const submitSearch = function () {
                if (form) {
                    form.submit();
                }
            };

            search.addEventListener('input', function () {
                window.clearTimeout(timer);
                timer = window.setTimeout(submitSearch, 350);
            });
            search.addEventListener('change', submitSearch);
        }
    })();
</script>
@endsection
