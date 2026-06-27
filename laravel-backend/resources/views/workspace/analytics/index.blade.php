@extends('workspace.layouts.app')

@section('title', __('portal.analytics.meta_title'))

@section('styles')
<style>
    .analytics-page {
        --analytics-card: #ffffff;
        --analytics-border: #eef0f3;
        --analytics-muted: #6b7280;
        --analytics-strong: #111827;
        --analytics-green: #10b981;
        --analytics-green-soft: #d1fae5;
        --analytics-primary: #3b82f6;
        --analytics-shadow: 0 10px 40px -10px rgba(0,0,0,0.06);
        --analytics-shadow-hover: 0 20px 40px -10px rgba(0,0,0,0.1);
        --radius-lg: 24px;
        --radius-md: 16px;
        font-family: 'Inter', 'Tajawal', sans-serif;
    }

    [data-theme="dark"] .analytics-page {
        --analytics-card: #1f2937;
        --analytics-border: #374151;
        --analytics-muted: #9ca3af;
        --analytics-strong: #f9fafb;
        --analytics-shadow: 0 10px 40px -10px rgba(0,0,0,0.4);
        --analytics-shadow-hover: 0 20px 40px -10px rgba(0,0,0,0.6);
    }

    .analytics-page {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: none; }
    }

    .analytics-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 420px);
        gap: 24px;
        align-items: stretch;
        margin-bottom: 32px;
    }

    .analytics-panel {
        background: var(--analytics-card);
        border: 1px solid var(--analytics-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--analytics-shadow);
        padding: 32px;
        position: relative;
        overflow: hidden;
    }

    .analytics-hero .analytics-panel:first-child::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, var(--analytics-green-soft) 0%, transparent 70%);
        border-radius: 50%;
        opacity: 0.5;
        z-index: 0;
    }

    .analytics-title {
        position: relative;
        margin: 0 0 12px;
        color: var(--analytics-strong);
        font-size: clamp(28px, 3.5vw, 42px);
        font-weight: 800;
        letter-spacing: -1px;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .analytics-subtitle {
        position: relative;
        margin: 0;
        color: var(--analytics-muted);
        font-size: 16px;
        line-height: 1.6;
        font-weight: 500;
        z-index: 1;
        max-width: 80%;
    }

    .analytics-filter {
        display: grid;
        gap: 16px;
    }

    .analytics-filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .analytics-field label {
        display: block;
        margin-bottom: 6px;
        color: var(--analytics-muted);
        font-weight: 600;
        font-size: 13px;
    }

    .analytics-field select,
    .analytics-field input {
        width: 100%;
        border: 1px solid var(--analytics-border);
        border-radius: 12px;
        padding: 10px 14px;
        background: transparent;
        color: var(--analytics-strong);
        font-weight: 500;
        transition: all 0.2s;
    }

    .analytics-field select:focus,
    .analytics-field input:focus {
        outline: none;
        border-color: var(--analytics-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .analytics-filter button {
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        background: var(--analytics-strong);
        color: var(--analytics-card);
        border: none;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .analytics-filter button:hover {
        transform: translateY(-2px);
        box-shadow: var(--analytics-shadow-hover);
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .analytics-stat {
        background: var(--analytics-card);
        border: 1px solid var(--analytics-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--analytics-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .analytics-stat:hover {
        transform: translateY(-4px);
        box-shadow: var(--analytics-shadow-hover);
        border-color: var(--analytics-green);
    }

    .analytics-stat .label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--analytics-muted);
        font-weight: 600;
        font-size: 14px;
    }

    .analytics-stat .label i {
        background: var(--analytics-green-soft);
        color: var(--analytics-green);
        padding: 10px;
        border-radius: 12px;
        font-size: 16px;
    }

    .analytics-stat .value {
        color: var(--analytics-strong);
        font-size: clamp(32px, 3vw, 40px);
        font-weight: 800;
        letter-spacing: -1px;
    }

    .analytics-stat .value.green {
        background: linear-gradient(135deg, var(--analytics-green) 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .analytics-stat .hint {
        color: var(--analytics-muted);
        font-size: 13px;
        font-weight: 500;
        padding-top: 12px;
        border-top: 1px dashed var(--analytics-border);
    }

    .analytics-section {
        margin-bottom: 32px;
    }

    .analytics-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .analytics-section h2 {
        margin: 0;
        color: var(--analytics-strong);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .analytics-mini-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .analytics-mini-card {
        background: var(--analytics-card);
        border: 1px solid var(--analytics-border);
        border-radius: var(--radius-md);
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        transition: transform 0.2s;
    }

    .analytics-mini-card:hover {
        transform: translateY(-2px);
    }

    .analytics-mini-card strong {
        color: var(--analytics-strong);
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .analytics-mini-card span {
        color: var(--analytics-muted);
        font-size: 13px;
        font-weight: 500;
    }

    .analytics-chart {
        display: flex;
        align-items: end;
        gap: 12px;
        min-height: 280px;
        padding: 32px;
        background: var(--analytics-card);
        border: 1px solid var(--analytics-border);
        border-radius: var(--radius-lg);
        overflow-x: auto;
        box-shadow: var(--analytics-shadow);
    }

    .analytics-bar {
        flex: 1;
        min-width: 48px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        height: 100%;
        position: relative;
    }

    .analytics-bar:hover .analytics-bar-fill {
        filter: brightness(1.1);
        transform: scaleY(1.02);
    }

    .analytics-bar-fill {
        width: 100%;
        height: max(12px, calc(var(--bar-height) * 1%));
        min-height: 12px;
        border-radius: 8px 8px 4px 4px;
        background: linear-gradient(180deg, var(--analytics-green) 0%, #059669 100%);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: height 1s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s;
        transform-origin: bottom;
    }

    .analytics-bar small {
        color: var(--analytics-muted);
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .analytics-two-col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .analytics-list {
        background: var(--analytics-card);
        border: 1px solid var(--analytics-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--analytics-shadow);
    }

    .analytics-list-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid var(--analytics-border);
        transition: background 0.2s;
    }

    .analytics-list-row:hover {
        background: var(--analytics-green-soft);
    }

    .analytics-list-row:last-child {
        border-bottom: 0;
    }

    .analytics-list-row b {
        color: var(--analytics-strong);
        font-size: 15px;
        font-weight: 700;
        display: block;
    }

    .analytics-list-row small {
        color: var(--analytics-muted);
        display: block;
        margin-top: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .analytics-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 6px 14px;
        background: var(--analytics-green-soft);
        color: var(--analytics-green);
        font-weight: 700;
        font-size: 13px;
        white-space: nowrap;
    }

    @media (max-width: 1024px) {
        .analytics-hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .analytics-two-col {
            grid-template-columns: 1fr;
        }
        .analytics-filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $money = fn (float|int $value) => number_format((float) $value, 2).' '.__('portal.analytics.currency');
    $number = fn (float|int $value) => number_format((float) $value, is_float($value) && floor($value) !== $value ? 1 : 0);
@endphp

<div class="analytics-page">
    <div class="analytics-hero">
        <section class="analytics-panel">
            <h1 class="analytics-title">
                <i class="fa-solid fa-chart-line" style="color:var(--upwork-green);"></i>
                {{ __('portal.analytics.title') }}
            </h1>
            <p class="analytics-subtitle">{{ __('portal.analytics.subtitle') }}</p>
        </section>

        <form class="analytics-panel analytics-filter" method="GET" action="{{ route('workspace.analytics.index') }}">
            <h2 style="margin:0; font-size:22px; font-weight:950;">{{ __('portal.analytics.filters_title') }}</h2>
            <div class="analytics-field">
                <label for="period">{{ __('portal.analytics.period') }}</label>
                <select name="period" id="period">
                    @foreach(['today', '7_days', '30_days', 'month', 'custom'] as $period)
                        <option value="{{ $period }}" @selected($filters['period'] === $period)>
                            {{ __('portal.analytics.period_'.$period) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="analytics-filter-row">
                <div class="analytics-field">
                    <label for="from">{{ __('portal.analytics.from') }}</label>
                    <input type="date" id="from" name="from" value="{{ $filters['from']->toDateString() }}">
                </div>
                <div class="analytics-field">
                    <label for="to">{{ __('portal.analytics.to') }}</label>
                    <input type="date" id="to" name="to" value="{{ $filters['to']->toDateString() }}">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">
                <i class="fa-solid fa-filter"></i>
                {{ __('portal.analytics.apply') }}
            </button>
        </form>
    </div>

    <section class="analytics-grid">
        <article class="analytics-stat">
            <div class="label">{{ __('portal.analytics.total_revenue') }} <i class="fa-solid fa-coins"></i></div>
            <div class="value green">{{ $money($summary['total_revenue_egp']) }}</div>
            <div class="hint">
                {{ __('portal.analytics.visit_revenue') }}: {{ $money($summary['visit_revenue_egp']) }}
            </div>
        </article>
        <article class="analytics-stat">
            <div class="label">{{ __('portal.analytics.visits') }} <i class="fa-solid fa-right-to-bracket"></i></div>
            <div class="value">{{ number_format($summary['visits']) }}</div>
            <div class="hint">
                {{ __('portal.analytics.unique_visitors') }}: {{ number_format($summary['unique_visitors']) }}
            </div>
        </article>
        <article class="analytics-stat">
            <div class="label">{{ __('portal.analytics.active_now') }} <i class="fa-solid fa-user-clock"></i></div>
            <div class="value green">{{ number_format($summary['active_now']) }}</div>
            <div class="hint">
                {{ __('portal.analytics.completed_visits') ?? __('portal.analytics.visits') }}: {{ number_format($summary['completed_visits']) }}
            </div>
        </article>
        <article class="analytics-stat">
            <div class="label">{{ __('portal.analytics.visit_hours') }} <i class="fa-solid fa-hourglass-half"></i></div>
            <div class="value">{{ $number($summary['visit_hours']) }}</div>
            <div class="hint">
                {{ __('portal.analytics.billable_hours') }}: {{ $number($summary['billable_hours']) }}
            </div>
        </article>
    </section>

    <section class="analytics-section">
        <div class="analytics-section-head">
            <h2>{{ __('portal.analytics.quick_summary') }}</h2>
        </div>
        <div class="analytics-mini-grid">
            <div class="analytics-mini-card"><strong>{{ $money($summary['room_revenue_egp']) }}</strong><span>{{ __('portal.analytics.room_revenue') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ $money($summary['private_session_revenue_egp']) }}</strong><span>{{ __('portal.analytics.private_session_revenue') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ $money($summary['subscription_revenue_egp']) }}</strong><span>{{ __('portal.analytics.subscription_revenue') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['active_subscriptions']) }}</strong><span>{{ __('portal.analytics.active') }} - {{ __('portal.analytics.subscriptions') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['low_hour_subscriptions']) }}</strong><span>{{ __('portal.analytics.low_hours') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['expiring_subscriptions']) }}</strong><span>{{ __('portal.analytics.expiring_soon') }}</span></div>
        </div>
    </section>

    <section class="analytics-section">
        <div class="analytics-section-head">
            <h2>{{ __('portal.analytics.trend_title') }}</h2>
        </div>
        <div class="analytics-chart">
            @foreach($trend as $day)
                <div class="analytics-bar" title="{{ $day['label'] }} - {{ $day['visits'] }}">
                    <div class="analytics-bar-fill" style="--bar-height: {{ $day['percent'] }}"></div>
                    <small>{{ $day['label'] }}<br>{{ $day['visits'] }}</small>
                </div>
            @endforeach
        </div>
    </section>

    <section class="analytics-section">
        <div class="analytics-section-head">
            <h2>{{ __('portal.analytics.operations') }}</h2>
        </div>
        <div class="analytics-mini-grid">
            <div class="analytics-mini-card"><strong>{{ number_format($operations['active_rooms_count']) }} / {{ number_format($operations['rooms_count']) }}</strong><span>{{ __('portal.analytics.rooms') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['active_room_reservations']) }}</strong><span>{{ __('portal.analytics.room_bookings') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['public_sessions']) }}</strong><span>{{ __('portal.analytics.public_sessions') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['private_sessions']) }}</strong><span>{{ __('portal.analytics.private_sessions') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['private_attended']) }} / {{ number_format($operations['private_invited']) }}</strong><span>{{ __('portal.analytics.private_attendance') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['private_qr_checkins']) }} / {{ number_format($operations['private_owner_checkins']) }}</strong><span>{{ __('portal.analytics.qr_vs_owner') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['notification_campaigns']) }}</strong><span>{{ __('portal.analytics.notifications') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ number_format($operations['notifications_sent']) }}</strong><span>{{ __('portal.analytics.sent') }}</span></div>
            <div class="analytics-mini-card"><strong>{{ $number($operations['notification_open_rate']) }}%</strong><span>{{ __('portal.analytics.open_rate') }}</span></div>
        </div>
    </section>

    <section class="analytics-two-col">
        <div class="analytics-section">
            <div class="analytics-section-head"><h2>{{ __('portal.analytics.top_clients') }}</h2></div>
            <div class="analytics-list">
                @forelse($topClients as $client)
                    <div class="analytics-list-row">
                        <div>
                            <b>{{ $client->full_name_snapshot ?? __('portal.analytics.empty') }}</b>
                            <small>{{ $client->phone_number_snapshot ?? '—' }}</small>
                        </div>
                        <span class="analytics-pill">{{ __('portal.analytics.visits_count', ['count' => number_format($client->total_visits)]) }}</span>
                    </div>
                @empty
                    <div class="analytics-list-row"><b>{{ __('portal.analytics.empty') }}</b></div>
                @endforelse
            </div>
        </div>

        <div class="analytics-section">
            <div class="analytics-section-head"><h2>{{ __('portal.analytics.top_rooms') }}</h2></div>
            <div class="analytics-list">
                @forelse($topRooms as $room)
                    <div class="analytics-list-row">
                        <div>
                            <b>{{ $room->room?->name ?? __('portal.analytics.rooms') }}</b>
                            <small>{{ __('portal.analytics.reservations_count', ['count' => number_format($room->reservations_count)]) }}</small>
                        </div>
                        <span class="analytics-pill">{{ $money(((int) $room->revenue_cents) / 100) }}</span>
                    </div>
                @empty
                    <div class="analytics-list-row"><b>{{ __('portal.analytics.empty') }}</b></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="analytics-two-col">
        <div class="analytics-section">
            <div class="analytics-section-head"><h2>{{ __('portal.analytics.top_private_sessions') }}</h2></div>
            <div class="analytics-list">
                @forelse($topPrivateSessions as $session)
                    <div class="analytics-list-row">
                        <div>
                            <b>{{ $session->title }}</b>
                            <small>{{ __('portal.analytics.attended_count', ['count' => number_format($session->attended_count ?? 0)]) }}</small>
                        </div>
                        <span class="analytics-pill">{{ $money(((int) ($session->attended_revenue_cents ?? 0)) / 100) }}</span>
                    </div>
                @empty
                    <div class="analytics-list-row"><b>{{ __('portal.analytics.empty') }}</b></div>
                @endforelse
            </div>
        </div>

        <div class="analytics-section">
            <div class="analytics-section-head"><h2>{{ __('portal.analytics.education_breakdown') }}</h2></div>
            <div class="analytics-list">
                <div class="analytics-list-row">
                    <div>
                        <b>{{ __('portal.analytics.teachers') }}</b>
                        <small>
                            @forelse($educationBreakdown['teachers'] as $teacher)
                                {{ $teacher['name'] }} ({{ $teacher['count'] }}){{ ! $loop->last ? '، ' : '' }}
                            @empty
                                {{ __('portal.analytics.empty') }}
                            @endforelse
                        </small>
                    </div>
                </div>
                <div class="analytics-list-row">
                    <div>
                        <b>{{ __('portal.analytics.subjects') }}</b>
                        <small>
                            @forelse($educationBreakdown['subjects'] as $subject)
                                {{ $subject['name'] }} ({{ $subject['count'] }}){{ ! $loop->last ? '، ' : '' }}
                            @empty
                                {{ __('portal.analytics.empty') }}
                            @endforelse
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
