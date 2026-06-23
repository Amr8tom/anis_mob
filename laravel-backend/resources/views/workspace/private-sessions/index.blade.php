@extends('workspace.layouts.app')

@section('title', __('portal.private_sessions.meta_title'))

@section('content')
<div class="private-session-list-page">
    <div class="private-session-list-hero">
        <div>
            <h1 class="private-session-list-title">{{ __('portal.private_sessions.title') }}</h1>
            <p class="private-session-list-subtitle">{{ __('portal.private_sessions.subtitle') }}</p>
        </div>
        <a href="{{ route('workspace.private-sessions.create') }}" class="btn-primary private-session-create-btn" style="text-decoration:none;"><i class="fa-solid fa-plus"></i> {{ __('portal.private_sessions.create_btn') }}</a>
    </div>

    <form action="{{ route('workspace.private-sessions.index') }}" method="GET" class="card private-session-dark-card private-session-filter-card">
        <div class="private-session-filter-grid">
            <div>
                <label>{{ __('portal.private_sessions.filter.teacher') }}</label>
                <select name="teacher" class="form-control">
                    <option value="">{{ __('portal.private_sessions.filter.all_teachers') }}</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(($filters['teacher'] ?? '') === $teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>{{ __('portal.private_sessions.filter.subject') }}</label>
                <select name="subject" class="form-control">
                    <option value="">{{ __('portal.private_sessions.filter.all_subjects') }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(($filters['subject'] ?? '') === $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>{{ __('portal.private_sessions.filter.grade') }}</label>
                <select name="grade_level" class="form-control">
                    <option value="">{{ __('portal.private_sessions.filter.all_grades') }}</option>
                    @foreach($gradeLevels as $gradeLevel)
                        <option value="{{ $gradeLevel->id }}" @selected(($filters['grade_level'] ?? '') === $gradeLevel->id)>{{ $gradeLevel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>{{ __('portal.private_sessions.filter.status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ __('portal.private_sessions.filter.all_statuses') }}</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>{{ __('portal.private_sessions.status.active') }}</option>
                    <option value="finished" @selected(($filters['status'] ?? '') === 'finished')>{{ __('portal.private_sessions.status.finished') }}</option>
                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>{{ __('portal.private_sessions.status.cancelled') }}</option>
                </select>
            </div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn-primary" type="submit"><i class="fa-solid fa-filter"></i> {{ __('portal.private_sessions.filter.apply') }}</button>
            <a href="{{ route('workspace.private-sessions.index') }}" class="private-session-clear-link">{{ __('portal.private_sessions.filter.clear') }}</a>
        </div>
    </form>

    @if($sessions->isEmpty())
        <div class="card empty-state private-session-dark-card">
            <i class="fa-solid fa-qrcode"></i>
            {{ __('portal.private_sessions.list.empty') }}
        </div>
    @else
        <div class="card private-session-dark-card">
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead>
                        <tr>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_session') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_education') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_schedule') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_price') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_attendance') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_revenue') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_instructor_net') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_status') }}</th>
                            <th style="padding:10px;">{{ __('portal.private_sessions.list.th_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                            @php $summary = $session->summary(); @endphp
                            <tr>
                                <td style="padding:10px; font-weight:700;">{{ $session->title }}</td>
                                <td style="padding:10px;">
                                    <div style="font-weight:800;">{{ $session->centerTeacher?->name ?? $session->host_name ?? '—' }}</div>
                                    <small>{{ $session->centerSubject?->name ?? __('portal.private_sessions.no_subject') }} · {{ $session->centerGradeLevel?->name ?? __('portal.private_sessions.no_grade') }}</small>
                                </td>
                                <td style="padding:10px;"><small>{{ $session->starts_at->format('Y/m/d H:i') }}</small></td>
                                <td style="padding:10px;">{{ number_format($session->priceEgp(), 2) }} {{ __('portal.egp') }}</td>
                                <td style="padding:10px;">{{ $summary['attended'] }} / {{ $summary['invited'] }}</td>
                                <td class="private-session-money" style="padding:10px;">{{ number_format($summary['actual_revenue_cents'] / 100, 2) }} {{ __('portal.egp') }}</td>
                                <td style="padding:10px;">
                                    <div>{{ number_format($summary['instructor_payout_cents'] / 100, 2) }} {{ __('portal.egp') }}</div>
                                    <small>{{ __('portal.private_sessions.list.net') }} {{ number_format($summary['center_net_cents'] / 100, 2) }} {{ __('portal.egp') }}</small>
                                </td>
                                <td style="padding:10px;"><span class="private-session-status private-session-status-{{ $session->status }}">{{ __('portal.private_sessions.status.'.$session->status) }}</span></td>
                                <td style="padding:10px;"><a href="{{ route('workspace.private-sessions.show', $session) }}" class="private-session-manage-link">{{ __('portal.private_sessions.list.manage') }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $sessions->links('vendor.pagination.upwork') }}</div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .private-session-list-page {
        --ps-surface: #16211b;
        --ps-surface-2: #1d2c24;
        --ps-input: #0f1813;
        --ps-border: #2c3d33;
        --ps-text: #eef3f0;
        --ps-muted: #9db0a4;
        --ps-dim: #6c7e73;
        --ps-accent: #2bd968;
    }

    .private-session-list-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        padding: 24px;
        border-radius: var(--radius-md);
        border: 1px solid var(--ps-border);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .13), transparent 28%),
            linear-gradient(135deg, var(--ps-surface, #16211b) 0%, var(--ps-surface-2, #1d2c24) 100%);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .private-session-list-title {
        margin: 0 0 8px;
        color: var(--ps-text);
        font-size: 34px;
        font-weight: 900;
        line-height: 1.2;
    }

    .private-session-list-subtitle {
        margin: 0;
        color: var(--ps-muted);
        font-size: 15px;
        font-weight: 700;
    }

    .private-session-create-btn {
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding-inline: 22px;
    }

    .private-session-list-page .private-session-dark-card {
        background: var(--ps-surface) !important;
        color: var(--ps-text) !important;
        border: 1px solid var(--ps-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .private-session-filter-card {
        margin-bottom: 18px;
    }

    .private-session-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }

    .private-session-list-page label {
        color: var(--ps-muted);
    }

    .private-session-list-page .form-control {
        background: var(--ps-input) !important;
        border-color: var(--ps-border) !important;
        color: var(--ps-text) !important;
    }

    .private-session-clear-link {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border: 1px solid var(--ps-border);
        border-radius: var(--radius-sm);
        color: var(--ps-text);
        text-decoration: none;
        font-weight: 800;
    }

    .private-session-list-page table {
        color: var(--ps-text);
    }

    .private-session-list-page thead tr {
        background: var(--ps-surface-2) !important;
        border-bottom: 2px solid var(--ps-border) !important;
    }

    .private-session-list-page tbody tr {
        border-bottom: 1px solid var(--ps-border) !important;
    }

    .private-session-list-page tbody tr:hover {
        background: var(--ps-row-hover, #21322a);
    }

    .private-session-list-page th {
        color: var(--ps-muted);
        font-weight: 800;
    }

    .private-session-list-page td,
    .private-session-list-page small {
        color: var(--ps-text);
    }

    .private-session-money {
        color: var(--ps-accent) !important;
        font-weight: 900;
    }

    .private-session-manage-link {
        color: #7ef7a4;
        text-decoration: none;
        font-weight: 800;
    }

    .private-session-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid var(--ps-border);
        color: var(--ps-muted);
        background: var(--ps-input);
    }

    .private-session-status-active {
        color: #7ef7a4;
        background: rgba(43, 217, 104, .12);
        border-color: rgba(43, 217, 104, .34);
    }

    .private-session-status-finished {
        color: #bfdbfe;
        background: rgba(59, 130, 246, .14);
        border-color: rgba(96, 165, 250, .28);
    }

    .private-session-status-cancelled {
        color: #fecaca;
        background: rgba(185, 28, 28, .16);
        border-color: rgba(248, 113, 113, .32);
    }

    .private-session-list-page .empty-state {
        color: var(--ps-muted);
    }

    @media (max-width: 1000px) {
        .private-session-filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
