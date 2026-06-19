@extends('workspace.layouts.app')

@section('title', 'تسجيل الزوار | بوابة مساحة العمل')

@section('content')
<div class="settings-container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 class="page-title" style="margin:0;">تسجيل دخول وخروج الزوار</h1>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); color:var(--upwork-green-dark);">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="card" style="margin-bottom:16px; border-right:4px solid #e23d3d; color:#b91c1c;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ── Stats ─────────────────────────────────────────────── --}}

    {{-- Active now: full-width highlight card --}}
    <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); display:flex; align-items:center; gap:20px; padding:18px 24px;">
        <div style="font-size:48px; font-weight:900; color:var(--upwork-green); line-height:1;">{{ $stats['active'] }}</div>
        <div>
            <div style="font-weight:800; font-size:18px;">حاضرون الآن</div>
            <div style="color:var(--upwork-muted); font-size:13px; margin-top:4px;">ساعات اليوم (مكتملة + جارية): <strong>{{ $stats['today_hours'] }} ساعة</strong></div>
        </div>
    </div>

    {{-- Period breakdown: 3 columns --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:22px;">
        @php
            $periodCards = [
                [
                    'period'   => 'اليوم',
                    'visitors' => $stats['today_visitors'],
                    'hours'    => $stats['today_hours'],
                    'color'    => '#14a800',
                    'note'     => 'يشمل الجلسات الجارية حالياً',
                ],
                [
                    'period'   => 'آخر 7 أيام',
                    'visitors' => $stats['week_visitors'],
                    'hours'    => $stats['week_hours'],
                    'color'    => '#0c9200',
                    'note'     => 'الزيارات المكتملة فقط',
                ],
                [
                    'period'   => 'هذا الشهر',
                    'visitors' => $stats['month_visitors'],
                    'hours'    => $stats['month_hours'],
                    'color'    => '#075200',
                    'note'     => 'الزيارات المكتملة فقط',
                ],
            ];
        @endphp
        @foreach($periodCards as $card)
            <div class="card" style="text-align:center; padding:20px;">
                <div style="font-size:13px; font-weight:700; color:var(--upwork-muted); margin-bottom:10px; text-transform:uppercase; letter-spacing:.5px;">
                    {{ $card['period'] }}
                </div>
                <div style="display:flex; justify-content:center; gap:24px; margin-bottom:8px;">
                    <div>
                        <div style="font-size:28px; font-weight:900; color:{{ $card['color'] }};">{{ $card['visitors'] }}</div>
                        <div style="font-size:12px; color:var(--upwork-muted); margin-top:2px;">زيارة</div>
                    </div>
                    <div style="width:1px; background:var(--upwork-border);"></div>
                    <div>
                        <div style="font-size:28px; font-weight:900; color:#f4a800;">{{ $card['hours'] }}</div>
                        <div style="font-size:12px; color:var(--upwork-muted); margin-top:2px;">ساعة</div>
                    </div>
                </div>
                <div style="font-size:11px; color:var(--upwork-muted);">{{ $card['note'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- ── Manual check-in form ──────────────────────────────── --}}
    <div class="card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 6px;">تسجيل دخول زائر</h3>
        <p style="color:var(--upwork-muted); margin:0 0 16px; font-size:14px;">
            أدخل رقم هاتف أي زائر. مستخدمو الباقات الفضية والذهبية يجب أن يكون لديهم اشتراك نشط ورصيد لا يقل عن 15 دقيقة.
        </p>
        <form action="{{ route('workspace.visits.store') }}" method="POST"
              style="display:flex; gap:15px; align-items:flex-end; flex-wrap:wrap;">
            @csrf
            <div style="flex:1; min-width:200px;">
                <label style="display:block; font-weight:700; font-size:14px; margin-bottom:6px;">رقم الهاتف</label>
                <input type="text" name="phone_number" class="form-control" placeholder="01xxxxxxxxx"
                       value="{{ old('phone_number') }}" required style="direction:ltr; text-align:right;">
            </div>
            <div style="flex:1; min-width:200px;">
                <label style="display:block; font-weight:700; font-size:14px; margin-bottom:6px;">الاسم (للزائر الجديد فقط)</label>
                <input type="text" name="name" class="form-control" placeholder="اسم الزائر" value="{{ old('name') }}">
            </div>
            <div>
                <button type="submit" class="btn-action btn-action-primary"
                        style="padding:12px 26px; border-radius:var(--radius-sm); border:none; color:#fff; background:var(--upwork-green); cursor:pointer; font-weight:800; height: 45px;">
                    تسجيل دخول
                </button>
            </div>
        </form>
    </div>

    @if(session('paid_visitor_verification'))
        @php
            $verification = session('paid_visitor_verification');
        @endphp
        <div id="paid-verification-modal" role="dialog" aria-modal="true" aria-labelledby="paid-verification-title"
             style="position:fixed; inset:0; z-index:1000; background:rgba(0,30,0,.55); display:flex; align-items:center; justify-content:center; padding:20px;">
            <div class="card" style="width:min(100%, 500px); margin:0; box-shadow:0 24px 80px rgba(0,0,0,.22);">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:18px;">
                    <div>
                        <div style="display:inline-flex; align-items:center; gap:6px; background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:5px 10px; border-radius:99px; font-size:12px; font-weight:800; margin-bottom:10px;">
                            <i class="fa-solid fa-shield-halved"></i>
                            تأكيد حضور {{ ($verification['billing_source'] ?? 'GLOBAL_SUBSCRIPTION') === 'WORKSPACE_SUBSCRIPTION' ? 'اشتراك مساحة' : 'عالمي' }}
                        </div>
                        <h3 id="paid-verification-title" style="margin:0 0 6px;">يؤكد الزائر وجوده بنفسه</h3>
                        <p style="margin:0; color:var(--upwork-muted); font-size:14px;">
                            اطلب من <strong>{{ $verification['visitor_name'] }}</strong> إدخال كلمة مرور حسابه لإتمام تسجيل الدخول.
                        </p>
                    </div>
                    <button type="button" onclick="document.getElementById('paid-verification-modal').remove()"
                            aria-label="إغلاق"
                            style="width:34px; height:34px; border:1px solid var(--upwork-border); background:#fff; border-radius:50%; cursor:pointer; color:var(--upwork-muted);">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('workspace.visits.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="phone_number" value="{{ $verification['phone_number'] }}">
                    <label for="visitor-password">كلمة مرور الزائر</label>
                    <input id="visitor-password" type="password" name="visitor_password" class="form-control"
                           required maxlength="72" autocomplete="current-password" autofocus
                           placeholder="يدخلها الزائر بنفسه">
                    <p style="font-size:12px; color:var(--upwork-muted); margin:-6px 0 18px;">
                        لا يتم حفظ كلمة المرور أو عرضها لمالك مساحة العمل.
                    </p>
                    <div style="display:flex; gap:10px; justify-content:flex-end;">
                        <button type="button" onclick="document.getElementById('paid-verification-modal').remove()"
                                style="padding:11px 18px; border:1px solid var(--upwork-border); border-radius:var(--radius-sm); background:#fff; cursor:pointer; font-weight:700;">
                            إلغاء
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-check"></i>
                            تأكيد وتسجيل الدخول
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Active visitors (auto-refreshing) ─────────────────── --}}
    <div class="card" style="margin-bottom:22px;" id="active-visitors-container"
         data-poll-url="{{ route('workspace.visits.index') }}?partial=active">
        @include('workspace.visits.partials.active-table')
    </div>

    {{-- ── Recent closed visits ──────────────────────────────── --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h3 style="margin:0 0 4px;">أحدث الزيارات المنتهية</h3>
                <p style="margin:0; color:var(--upwork-muted); font-size:13px;">يعرض السجل منذ آخر عملية مسح للعرض، دون حذف بيانات الحضور.</p>
            </div>
            <form action="{{ route('workspace.visits.clear-recent') }}" method="POST" onsubmit="return confirm('سيتم إخفاء الزيارات الحالية من هذه القائمة فقط. هل تريد المتابعة؟');">
                @csrf
                <button type="submit" style="padding:10px 16px; border-radius:var(--radius-sm); border:1px solid var(--upwork-error); color:var(--upwork-error); background:#fff; cursor:pointer; font-weight:700;">
                    <i class="fa-solid fa-broom"></i> مسح أحدث الزيارات من العرض
                </button>
            </form>
        </div>

        <form action="{{ route('workspace.visits.index') }}" method="GET" style="padding:16px; background:var(--upwork-bg); border:1px solid var(--upwork-border); border-radius:var(--radius-sm); margin-bottom:18px;">
            <div class="grid-4" style="gap:12px;">
                <div>
                    <label for="recent-search">الاسم أو الهاتف</label>
                    <input id="recent-search" type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ابحث عن زائر">
                </div>
                <div>
                    <label for="recent-from">من تاريخ ووقت</label>
                    <input id="recent-from" type="datetime-local" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div>
                    <label for="recent-to">إلى تاريخ ووقت</label>
                    <input id="recent-to" type="datetime-local" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div>
                    <label for="recent-plan">الباقة</label>
                    <select id="recent-plan" name="plan" class="form-control">
                        <option value="">كل الباقات</option>
                        <option value="FREE" @selected(request('plan') === 'FREE')>مجاني</option>
                        <option value="GLOBAL_SUBSCRIPTION" @selected(request('plan') === 'GLOBAL_SUBSCRIPTION')>عالمي</option>
                        <option value="WORKSPACE_SUBSCRIPTION" @selected(request('plan') === 'WORKSPACE_SUBSCRIPTION')>اشتراك مساحة</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> تطبيق الفلاتر</button>
                <a href="{{ route('workspace.visits.index') }}" style="padding:11px 18px; border:1px solid var(--upwork-border); border-radius:var(--radius-sm); text-decoration:none; color:var(--upwork-slate); font-weight:700;">مسح الفلاتر</a>
            </div>
        </form>

        @if($recentVisits->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:24px;">لا توجد زيارات منتهية بعد.</p>
        @else
            <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead>
                    <tr style="border-bottom:2px solid var(--upwork-border);">
                        <th style="padding:10px 12px;">الزائر</th>
                        <th style="padding:10px 12px;">الباقة</th>
                        <th style="padding:10px 12px;">الدخول</th>
                        <th style="padding:10px 12px;">الخروج</th>
                        <th style="padding:10px 12px;">المدة</th>
                        <th style="padding:10px 12px;">دقائق مخصومة</th>
                        <th style="padding:10px 12px;">الإيراد التقديري</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Room reservations (own price, badge حجز غرفة) folded into this table --}}
                    @foreach(($roomReservations ?? collect()) as $res)
                        @php
                            $rmins = $res->durationMinutes();
                            $rh = intdiv($rmins, 60); $rm = $rmins % 60;
                            $rLabel = $rh > 0 ? "{$rh}س {$rm}د" : "{$rm}د";
                        @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border); background:#fff8ef;">
                            <td style="padding:10px 12px; font-weight:bold;">{{ $res->client_name }}<br><small style="color:var(--upwork-muted);">{{ $res->room->name ?? 'غرفة' }}</small></td>
                            <td style="padding:10px 12px;"><span style="background:#7a4dff; color:#fff; padding:3px 8px; border-radius:20px; font-size:12px; font-weight:700;">حجز غرفة</span></td>
                            <td style="padding:10px 12px;"><small>{{ $res->starts_at->format('m/d H:i') }}</small></td>
                            <td style="padding:10px 12px;"><small>{{ $res->ends_at->format('m/d H:i') }}</small></td>
                            <td style="padding:10px 12px; font-weight:700;">{{ $rLabel }}</td>
                            <td style="padding:10px 12px; color:var(--upwork-muted);">—</td>
                            <td style="padding:10px 12px; font-weight:700; color:var(--upwork-green-dark);">{{ number_format($res->totalCostEgp(), 2) }} ج.م</td>
                        </tr>
                    @endforeach
                    @foreach($recentVisits as $visit)
                        @php
                            $mins    = $visit->duration_minutes ?? 0;
                            $h       = intdiv($mins, 60);
                            $m       = $mins % 60;
                            $dLabel  = $h > 0 ? "{$h}س {$m}د" : "{$m}د";
                        @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px 12px; font-weight:bold;">{{ $visit->visitor_name }}</td>
                            <td style="padding:10px 12px;">
                                @if(($visit->billing_source?->value ?? 'FREE') === 'FREE')
                                    <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:3px 8px; border-radius:20px; font-size:12px; font-weight:700;">مجاني</span>
                                @elseif($visit->billing_source?->value === 'GLOBAL_SUBSCRIPTION')
                                    <span style="background:var(--upwork-blue); color:#fff; padding:3px 8px; border-radius:20px; font-size:12px; font-weight:700;">عالمي</span>
                                @else
                                    <span style="background:#ffb300; color:#fff; padding:3px 8px; border-radius:20px; font-size:12px; font-weight:700;">اشتراك مساحة</span>
                                @endif
                            </td>
                            <td style="padding:10px 12px;"><small>{{ $visit->check_in_at->format('m/d H:i') }}</small></td>
                            <td style="padding:10px 12px;"><small>{{ $visit->check_out_at?->format('m/d H:i') }}</small></td>
                            <td style="padding:10px 12px; font-weight:700;">{{ $dLabel }}</td>
                            <td style="padding:10px 12px; color:var(--upwork-muted);">
                                {{ $visit->deducted_minutes ?? '—' }}
                                @if($visit->hour_multiplier_applied && $visit->hour_multiplier_applied != 1.0)
                                    <small style="color:var(--upwork-muted);">(×{{ $visit->hour_multiplier_applied }})</small>
                                @endif
                            </td>
                            <td style="padding:10px 12px; font-weight:700; color:var(--upwork-green-dark);">
                                {{ number_format(($mins / 60) * $workspace->effectiveHourlyRateEgp(), 2) }} ج.م
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:var(--upwork-green-soft); border-top:2px solid var(--upwork-green);">
                        <td colspan="2" style="padding:14px 12px; font-weight:900;">إجمالي النتائج: {{ number_format($recentSummary['total_visitors']) }} زائر</td>
                        <td style="padding:14px 12px; font-weight:900;">{{ number_format($recentSummary['total_visits']) }} زيارة</td>
                        <td colspan="2" style="padding:14px 12px; font-weight:900;">{{ number_format($recentSummary['total_minutes'] / 60, 2) }} ساعة</td>
                        <td colspan="2" style="padding:14px 12px; font-weight:900; color:var(--upwork-green-dark);">{{ number_format($recentSummary['total_revenue'], 2) }} ج.م</td>
                    </tr>
                </tfoot>
            </table>
            </div>
            {{ $recentVisits->links('vendor.pagination.upwork') }}
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    @keyframes anisPulse {
        0%   { box-shadow: 0 0 0 0 rgba(244,168,0,0.45); }
        70%  { box-shadow: 0 0 0 8px rgba(244,168,0,0); }
        100% { box-shadow: 0 0 0 0 rgba(244,168,0,0); }
    }
    .checkout-req-badge { animation: anisPulse 1.8s infinite; }
</style>
@endsection

@section('scripts')
<script>
    // Poll the active-visitors table so new "request to leave" entries appear
    // without a manual refresh. Pauses while the tab is hidden.
    (function () {
        const container = document.getElementById('active-visitors-container');
        if (!container) return;
        const url = container.dataset.pollUrl;
        let timer = null;

        async function refresh() {
            if (document.hidden) return;
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const html = await res.text();
                // Only swap if the pending-request state actually changed, so we
                // don't interrupt a confirm() dialog the owner is mid-way through.
                const current = container.querySelector('#active-visitors-card')?.dataset.pending;
                const tmp = document.createElement('div');
                tmp.innerHTML = html;
                const incoming = tmp.querySelector('#active-visitors-card')?.dataset.pending;
                if (current !== incoming) {
                    container.innerHTML = html;
                }
            } catch (e) { /* network hiccup — try again next tick */ }
        }

        timer = setInterval(refresh, 20000);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); });
    })();
</script>
@endsection
