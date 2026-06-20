@extends('workspace.layouts.app')

@section('title', 'تسجيل الزوا')

@section('content')
<div class="visits-dark">

    @if($errors->any())
        <div class="card" style="margin-bottom:16px; border-right:4px solid #e23d3d; color:#b91c1c;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ── Checkout Summary Modal (auto-dismiss 10s) ──────────── --}}
    @if(session('checkout_summary'))
        @php $cs = session('checkout_summary'); @endphp
        <div id="checkout-modal" style="position:fixed; inset:0; z-index:2000; background:rgba(0,0,0,0.82); backdrop-filter:blur(6px); display:flex; align-items:center; justify-content:center; padding:20px; animation:coFadeIn 0.25s ease;">
            <div style="width:min(100%, 420px); border-radius:var(--radius-md); overflow:hidden; background:#1a1a1a; color:#fff; box-shadow:0 28px 80px rgba(0,0,0,0.6); border:1px solid #2e2e2e; animation:coSlideUp 0.32s cubic-bezier(.2,.8,.2,1);">

                {{-- Countdown progress bar --}}
                <div style="height:4px; background:#2e2e2e; position:relative; overflow:hidden;">
                    <div id="co-progress" style="position:absolute; top:0; right:0; height:100%; background:var(--upwork-green); width:100%; transition:width 10s linear;"></div>
                </div>

                {{-- Header --}}
                <div style="padding:28px 24px 20px; text-align:center; border-bottom:1px solid #2e2e2e;">
                    <div style="width:56px; height:56px; border-radius:50%; background:rgba(20,168,0,0.15); display:grid; place-items:center; margin:0 auto 14px;">
                        <i class="fa-solid fa-circle-check" style="color:var(--upwork-green); font-size:28px;"></i>
                    </div>
                    <h3 style="margin:0 0 5px; font-size:19px; font-weight:900; color:#fff;">تم تسجيل الخروج بنجاح</h3>
                    <p style="margin:0; font-size:14px; color:#999; font-weight:600;">{{ $cs['visitor_name'] }}</p>
                </div>

                {{-- Details --}}
                <div style="padding:20px 24px 0;">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0; border-bottom:1px solid #2a2a2a;">
                        <span style="font-size:13px; color:#888; font-weight:700; display:flex; align-items:center; gap:7px;"><i class="fa-solid fa-clock"></i>المدة الإجمالية</span>
                        <span style="font-size:15px; font-weight:800; color:#f0f0f0;">{{ $cs['duration_label'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0; border-bottom:1px solid #2a2a2a;">
                        <span style="font-size:13px; color:#888; font-weight:700; display:flex; align-items:center; gap:7px;"><i class="fa-solid fa-right-to-bracket"></i>دخول</span>
                        <span style="font-size:14px; font-weight:700; color:#ccc;">{{ $cs['check_in_at'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0; border-bottom:1px solid #2a2a2a;">
                        <span style="font-size:13px; color:#888; font-weight:700; display:flex; align-items:center; gap:7px;"><i class="fa-solid fa-right-from-bracket"></i>خروج</span>
                        <span style="font-size:14px; font-weight:700; color:#ccc;">{{ $cs['check_out_at'] }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0;">
                        <span style="font-size:13px; color:#888; font-weight:700; display:flex; align-items:center; gap:7px;"><i class="fa-solid fa-tag"></i>الباقة</span>
                        <span style="font-size:13px; font-weight:700; color:#ccc;">
                            @if($cs['billing_source'] === 'FREE') مجاني
                            @elseif($cs['billing_source'] === 'GLOBAL_SUBSCRIPTION') اشتراك عالمي
                            @else اشتراك مساحة @endif
                        </span>
                    </div>
                </div>

                {{-- Revenue highlight --}}
                <div style="margin:16px 16px 0; padding:18px 20px; background:rgba(20,168,0,0.1); border:1px solid rgba(20,168,0,0.25); border-radius:var(--radius-sm); display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:14px; font-weight:800; color:#aaa; display:flex; align-items:center; gap:8px;"><i class="fa-solid fa-coins" style="color:var(--upwork-green);"></i>الإيراد التقديري</span>
                    <span style="font-size:26px; font-weight:900; color:var(--upwork-green); letter-spacing:-0.5px;">{{ $cs['price'] }} <small style="font-size:14px;">ج.م</small></span>
                </div>

                {{-- Close button + countdown --}}
                <div style="padding:16px 24px 20px; display:flex; align-items:center; gap:12px;">
                    <button onclick="closeCheckoutModal()" style="flex:1; padding:13px; border:none; border-radius:var(--radius-sm); background:var(--upwork-green); color:#fff; font-family:inherit; font-size:15px; font-weight:800; cursor:pointer;">
                        <i class="fa-solid fa-check"></i> إغلاق
                    </button>
                    <span id="co-countdown" style="font-size:13px; color:#666; font-weight:700; white-space:nowrap; min-width:48px; text-align:center;">10 ث</span>
                </div>
            </div>
        </div>
    @endif
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

    {{-- ── Active visitors + check-in form (manage-grid) ────────── --}}
    <div class="vd-card" style="padding:22px;">
        <div class="manage-grid">
            {{-- Active visitors (primary) --}}
            <div class="manage-main" id="active-visitors-container"
                 data-poll-url="{{ route('workspace.visits.index') }}?partial=active">
                @include('workspace.visits.partials.active-table')
            </div>

            {{-- Check-in form (sticky aside) --}}
            <aside class="manage-aside">
                <form action="{{ route('workspace.visits.store') }}" method="POST" class="manage-form-panel">
                    @csrf
                    <h4><i class="fa-solid fa-right-to-bracket" style="color:var(--vd-accent);"></i> تسجيل دخول زائر</h4>
                    <p style="color:var(--vd-text-muted); font-size:13px; margin:-6px 0 14px; font-weight:400;">أدخل رقم هاتف أي زائر. الباقات الفضية/الذهبية تتطلب اشتراكاً نشطاً ورصيداً لا يقل عن 15 دقيقة.</p>
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="01xxxxxxxxx"
                           value="{{ old('phone_number') }}" required style="direction:ltr; text-align:right;">
                    <label>الاسم (للزائر الجديد فقط)</label>
                    <input type="text" name="name" class="form-control" placeholder="اسم الزائر" value="{{ old('name') }}">
                    <button type="submit" class="btn-primary" style="width:100%;">
                        <i class="fa-solid fa-check"></i> تسجيل دخول
                    </button>
                </form>
            </aside>
        </div>
    </div>

    {{-- ── Recent closed visits ──────────────────────────────── --}}
    <div class="vd-card vd-card--accent">
        {{-- Section header --}}
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div>
                    <h3>أحدث الزيارات المنتهية</h3>
                    <p>يعرض السجل منذ آخر عملية مسح، دون حذف بيانات الحضور.</p>
                </div>
            </div>
            <form action="{{ route('workspace.visits.clear-recent') }}" method="POST" onsubmit="return confirm('سيتم إخفاء الزيارات الحالية من هذه القائمة فقط. هل تريد المتابعة؟');">
                @csrf
                <button type="submit" class="vd-btn"><i class="fa-solid fa-broom"></i> مسح العرض</button>
            </form>
        </div>

        <div style="padding: 20px 26px;">
            <div class="manage-grid">

                {{-- Table (primary) --}}
                <div class="manage-main">
                    @if($recentVisits->isEmpty() && ($roomReservations ?? collect())->isEmpty())
                        <div class="empty-state">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            لا توجد زيارات منتهية بعد.
                        </div>
                    @else
                        <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>الزائر</th>
                                    <th>الباقة</th>
                                    <th>الدخول</th>
                                    <th>الخروج</th>
                                    <th>المدة</th>
                                    <th>دقائق مخصومة</th>
                                    <th>الإيراد التقديري</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($roomReservations ?? collect()) as $res)
                                    @php
                                        $rmins = $res->durationMinutes();
                                        $rh = intdiv($rmins, 60); $rm = $rmins % 60;
                                        $rLabel = $rh > 0 ? "{$rh}س {$rm}د" : "{$rm}د";
                                    @endphp
                                    <tr class="vd-row-alt">
                                        <td style="font-weight:700;">
                                            {{ $res->client_name }}
                                            <br><small class="sub">{{ $res->room->name ?? 'غرفة' }}</small>
                                            @if($res->note)
                                                <br><small class="sub">ملاحظة: {{ $res->note }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-room">حجز غرفة</span></td>
                                        <td><small class="time">{{ $res->starts_at->format('m/d H:i') }}</small></td>
                                        <td><small class="time">{{ $res->ends_at->format('m/d H:i') }}</small></td>
                                        <td style="font-weight:700;">{{ $rLabel }}</td>
                                        <td class="dim">—</td>
                                        <td class="money">{{ number_format($res->totalCostEgp(), 2) }} <small style="font-size:11px;">ج.م</small></td>
                                    </tr>
                                @endforeach
                                @foreach($recentVisits as $visit)
                                    @php
                                        $mins    = $visit->duration_minutes ?? 0;
                                        $h       = intdiv($mins, 60);
                                        $m       = $mins % 60;
                                        $dLabel  = $h > 0 ? "{$h}س {$m}د" : "{$m}د";
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700;">{{ $visit->visitor_name }}</td>
                                        <td>
                                            @if(($visit->billing_source?->value ?? 'FREE') === 'FREE')
                                                <span class="badge badge-free">مجاني</span>
                                            @elseif($visit->billing_source?->value === 'GLOBAL_SUBSCRIPTION')
                                                <span class="badge badge-global">عالمي</span>
                                            @else
                                                <span class="badge badge-space">اشتراك مساحة</span>
                                            @endif
                                        </td>
                                        <td><small class="time">{{ $visit->check_in_at->format('m/d H:i') }}</small></td>
                                        <td><small class="time">{{ $visit->check_out_at?->format('m/d H:i') }}</small></td>
                                        <td style="font-weight:700;">{{ $dLabel }}</td>
                                        <td class="time">
                                            {{ $visit->deducted_minutes ?? '—' }}
                                            @if($visit->hour_multiplier_applied && $visit->hour_multiplier_applied != 1.0)
                                                <small class="dim">(×{{ $visit->hour_multiplier_applied }})</small>
                                            @endif
                                        </td>
                                        <td class="money">
                                            {{ number_format($workspace->estimatedRevenueEgp((int) $mins), 2) }} <small style="font-size:11px;">ج.م</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">إجمالي: {{ number_format($recentSummary['total_visitors']) }} زائر</td>
                                    <td>{{ number_format($recentSummary['total_visits']) }} زيارة</td>
                                    <td colspan="2">{{ number_format($recentSummary['total_minutes'] / 60, 2) }} ساعة</td>
                                    <td colspan="2" class="money">
                                        <i class="fa-solid fa-coins" style="margin-left:4px; opacity:0.85;"></i>
                                        {{ number_format($recentSummary['total_revenue'], 2) }} ج.م
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        {{ $recentVisits->links('vendor.pagination.upwork') }}
                    @endif
                </div>

                {{-- Filter form (sticky aside) --}}
                <aside class="manage-aside">
                    <form action="{{ route('workspace.visits.index') }}" method="GET" class="manage-form-panel">
                        <h4><i class="fa-solid fa-filter" style="color:var(--vd-text-muted);"></i> تصفية النتائج</h4>
                        <label for="recent-search">الاسم أو الهاتف</label>
                        <input id="recent-search" type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="ابحث عن زائر">
                        <label for="recent-from">من تاريخ</label>
                        <input id="recent-from" type="datetime-local" name="from" class="form-control" value="{{ request('from') }}">
                        <label for="recent-to">إلى تاريخ</label>
                        <input id="recent-to" type="datetime-local" name="to" class="form-control" value="{{ request('to') }}">
                        <label for="recent-plan">الباقة</label>
                        <select id="recent-plan" name="plan" class="form-control">
                            <option value="">كل الباقات</option>
                            <option value="FREE" @selected(request('plan') === 'FREE')>مجاني</option>
                            <option value="GLOBAL_SUBSCRIPTION" @selected(request('plan') === 'GLOBAL_SUBSCRIPTION')>عالمي</option>
                            <option value="WORKSPACE_SUBSCRIPTION" @selected(request('plan') === 'WORKSPACE_SUBSCRIPTION')>اشتراك مساحة</option>
                        </select>
                        <button type="submit" class="btn-primary" style="width:100%; box-shadow:none;">
                            <i class="fa-solid fa-filter"></i> تطبيق
                        </button>
                        @if(request()->hasAny(['search','from','to','plan']))
                            <a href="{{ route('workspace.visits.index') }}" class="vd-clear-link">
                                <i class="fa-solid fa-xmark"></i> مسح الفلاتر
                            </a>
                        @endif
                    </form>
                </aside>

            </div>
        </div>{{-- /padding wrapper --}}
    </div>
</div>
@endsection

@section('styles')
@include('workspace.partials.dark-theme')
<style>
    @keyframes anisPulse {
        0%   { box-shadow: 0 0 0 0 rgba(244,168,0,0.45); }
        70%  { box-shadow: 0 0 0 8px rgba(244,168,0,0); }
        100% { box-shadow: 0 0 0 0 rgba(244,168,0,0); }
    }
    .checkout-req-badge { animation: anisPulse 1.8s infinite; }

    @keyframes coFadeIn  { from { opacity:0 } to { opacity:1 } }
    @keyframes coSlideUp { from { opacity:0; transform:translateY(24px) scale(0.97) } to { opacity:1; transform:none } }
    @keyframes coFadeOut { to { opacity:0; transform:scale(0.96) } }
</style>
@endsection

@section('scripts')
<script>
    // ── Checkout modal: 10-second countdown + auto-dismiss ─────────
    function closeCheckoutModal() {
        const modal = document.getElementById('checkout-modal');
        if (!modal) return;
        modal.style.animation = 'coFadeOut 0.25s ease forwards';
        setTimeout(() => modal.remove(), 240);
    }

    (function () {
        const modal = document.getElementById('checkout-modal');
        if (!modal) return;

        const TOTAL = 10;
        const bar   = document.getElementById('co-progress');
        const label = document.getElementById('co-countdown');
        let remaining = TOTAL;

        // Trigger the shrink immediately (transition is 10s linear)
        requestAnimationFrame(() => { bar.style.width = '0%'; });

        const tick = setInterval(() => {
            remaining--;
            if (label) label.textContent = remaining + ' ث';
            if (remaining <= 0) {
                clearInterval(tick);
                closeCheckoutModal();
            }
        }, 1000);

        // Clicking the backdrop also closes
        modal.addEventListener('click', (e) => { if (e.target === modal) closeCheckoutModal(); });
    })();

    // ── Poll active-visitors table ──────────────────────────────────
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
