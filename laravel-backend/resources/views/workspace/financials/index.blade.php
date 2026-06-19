@extends('workspace.layouts.app')

@section('title', 'المالية | بوابة مساحة العمل')

@section('content')
<div class="settings-container">
    <h1 class="page-title">المالية والمدفوعات</h1>
    <p class="page-subtitle">مراجعة ساعات الحضور والمدفوعات المسجّلة من قِبل إدارة أنيس.</p>

    {{-- ── Period totals ─────────────────────────────────────────────────── --}}
    <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); padding:24px 28px;">
        <div style="display:flex; gap:40px; align-items:center; flex-wrap:wrap;">
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">إجمالي الساعات هذا الشهر</div>
                <div style="font-size:38px; font-weight:900; color:var(--upwork-green);">
                    {{ number_format($summary['total_minutes'] / 60, 1) }}
                    <span style="font-size:18px; font-weight:600;">ساعة</span>
                </div>
            </div>
            <div style="width:1px; height:60px; background:var(--upwork-border);"></div>
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">الزيارات</div>
                <div style="font-size:38px; font-weight:900;">{{ number_format($summary['total_visits']) }}</div>
            </div>
            <div style="width:1px; height:60px; background:var(--upwork-border);"></div>
            <div>
                <div style="color:var(--upwork-muted); font-size:13px; font-weight:700; margin-bottom:6px;">الزوار الفريدون</div>
                <div style="font-size:38px; font-weight:900;">{{ number_format($summary['unique_visitors']) }}</div>
            </div>
        </div>
        <p style="color:var(--upwork-muted); font-size:13px; margin-top:14px; margin-bottom:0;">
            إدارة أنيس تراجع ساعات الحضور وتسجّل المدفوعات التي تتم خارج التطبيق.
        </p>
    </div>

    {{-- ── Per-tier breakdown ────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">
        @foreach([
            ['مجاني',  'free',   '#5e6d55'],
            ['عالمي',    'global_subscription', '#607d8b'],
            ['مساحة',   'workspace_subscription',   '#f9a825'],
        ] as [$label, $key, $color])
            <div class="card" style="text-align:center; padding:20px;">
                <div style="color:{{ $color }}; font-weight:800; font-size:13px; margin-bottom:8px;">{{ $label }}</div>
                <div style="font-size:26px; font-weight:900;">
                    {{ number_format($summary[$key.'_minutes'] / 60, 1) }}
                    <span style="font-size:14px; font-weight:600;">ساعة</span>
                </div>
                <div style="font-size:12px; color:var(--upwork-muted); margin-top:4px;">
                    {{ number_format($summary[$key.'_visits']) }} زيارة
                    &nbsp;·&nbsp;
                    {{ number_format($summary[$key.'_visitors'] ?? 0) }} زائر
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Recorded payments ────────────────────────────────────────────── --}}
    <div class="card" style="padding:0;">
        <div style="padding:20px 24px; border-bottom:1px solid var(--upwork-border);">
            <h3 class="card-title" style="margin:0;">المدفوعات المسجّلة</h3>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead>
                    <tr style="border-bottom:2px solid var(--upwork-border); background:var(--upwork-bg);">
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">تاريخ الدفع</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">الفترة</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">الساعات</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">المبلغ</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">طريقة الدفع</th>
                        <th style="padding:12px 16px; font-size:13px; font-weight:700; color:var(--upwork-muted);">الملاحظة</th>
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
                                {{ number_format($settlement->total_minutes / 60, 1) }} س
                            </td>
                            <td style="padding:12px 16px; font-weight:700;">
                                {{ number_format($settlement->amount_cents / 100, 2) }}
                                <small style="color:var(--upwork-muted);">{{ $settlement->currency ?? 'EGP' }}</small>
                            </td>
                            <td style="padding:12px 16px; font-size:13px;">
                                {{ match($settlement->payment_method) {
                                    'CASH'           => 'نقدي',
                                    'BANK_TRANSFER'  => 'تحويل بنكي',
                                    'MOBILE_WALLET'  => 'محفظة موبايل',
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
                                لم يتم تسجيل أي مدفوعات بعد.
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
