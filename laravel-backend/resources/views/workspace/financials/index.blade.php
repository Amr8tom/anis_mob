@extends('workspace.layouts.app')

@section('title', __('portal.financials.meta_title'))

@section('content')
<div class="settings-container">
    <h1 class="page-title">{{ __('portal.financials.title') }}</h1>
    <p class="page-subtitle">{{ __('portal.financials.subtitle') }}</p>

    {{-- ── Period totals ─────────────────────────────────────────────────── --}}
    <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); padding:24px 28px;">
        <div style="display:flex; gap:40px; align-items:center; flex-wrap:wrap;">
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">{{ __('portal.financials.total_hours_month') }}</div>
                <div style="font-size:38px; font-weight:900; color:var(--upwork-green);">
                    {{ number_format($summary['total_minutes'] / 60, 1) }}
                    <span style="font-size:18px; font-weight:600;">{{ __('portal.financials.hour_unit') }}</span>
                </div>
            </div>
            <div style="width:1px; height:60px; background:var(--upwork-border);"></div>
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">{{ __('portal.financials.visits') }}</div>
                <div style="font-size:38px; font-weight:900;">{{ number_format($summary['total_visits']) }}</div>
            </div>
            <div style="width:1px; height:60px; background:var(--upwork-border);"></div>
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">{{ __('portal.financials.unique_visitors') }}</div>
                <div style="font-size:38px; font-weight:900;">{{ number_format($summary['unique_visitors']) }}</div>
            </div>
        </div>
        <p style="color:var(--upwork-muted); font-size:13px; margin-top:14px; margin-bottom:0;">
            {{ __('portal.financials.note') }}
        </p>
    </div>

    {{-- ── Per-tier breakdown ────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">
        @foreach([
            [__('portal.financials.tier_free'),  'free',   '#5e6d55'],
            [__('portal.financials.tier_global'),    'global_subscription', '#607d8b'],
            [__('portal.financials.tier_workspace'),   'workspace_subscription',   '#f9a825'],
        ] as [$label, $key, $color])
            <div class="card" style="text-align:center; padding:20px;">
                <div style="color:{{ $color }}; font-weight:800; font-size:13px; margin-bottom:8px;">{{ $label }}</div>
                <div style="font-size:26px; font-weight:900;">
                    {{ number_format($summary[$key.'_minutes'] / 60, 1) }}
                    <span style="font-size:14px; font-weight:600;">{{ __('portal.financials.hour_unit') }}</span>
                </div>
                <div style="font-size:12px; color:var(--upwork-muted); margin-top:4px;">
                    {{ __('portal.financials.visits_suffix', ['count' => number_format($summary[$key.'_visits'])]) }}
                    &nbsp;·&nbsp;
                    {{ __('portal.financials.visitors_suffix', ['count' => number_format($summary[$key.'_visitors'] ?? 0)]) }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Recorded payments ────────────────────────────────────────────── --}}
    <div class="card" style="padding:0;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--upwork-border);">
            <h3 class="card-title" style="margin:0;">{{ __('portal.financials.payments_title') }}</h3>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead>
                    <tr style="border-bottom:2px solid var(--upwork-border); background:var(--upwork-bg);">
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_pay_date') }}</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_period') }}</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_hours') }}</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_amount') }}</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_method') }}</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">{{ __('portal.financials.th_note') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $settlement)
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:12px 16px; font-weight:700;">
                                {{ $settlement->paid_at->format('Y-m-d') }}
                            </td>
                            <td style="padding:12px 16px; font-size:13px; color:var(--upwork-muted);">
                                {{ $settlement->period_started_at->format('Y-m-d') }}
                                &nbsp;→&nbsp;
                                {{ $settlement->period_ended_at->format('Y-m-d') }}
                            </td>
                            <td style="padding:12px 16px; font-weight:700; color:var(--upwork-green);">
                                {{ number_format($settlement->total_minutes / 60, 1) }} {{ __('portal.financials.hours_short') }}
                            </td>
                            <td style="padding:12px 16px; font-weight:700;">
                                {{ number_format($settlement->amount_cents / 100, 2) }}
                                <small style="color:var(--upwork-muted);">{{ $settlement->currency ?? 'EGP' }}</small>
                            </td>
                            <td style="padding:12px 16px; font-size:13px;">
                                {{ match($settlement->payment_method) {
                                    'CASH'           => __('portal.financials.method_cash'),
                                    'BANK_TRANSFER'  => __('portal.financials.method_bank'),
                                    'MOBILE_WALLET'  => __('portal.financials.method_wallet'),
                                    default          => $settlement->payment_method,
                                } }}
                            </td>
                            <td style="padding:12px 16px; font-size:13px; color:var(--upwork-muted);">
                                {{ $settlement->note ?? $settlement->payment_reference ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:48px; text-align:center; color:var(--upwork-muted);">
                                {{ __('portal.financials.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;">{{ $settlements->links() }}</div>
    </div>
</div>
@endsection
