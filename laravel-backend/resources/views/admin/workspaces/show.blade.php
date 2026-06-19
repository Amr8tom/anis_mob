@extends('admin.layout.app')

@section('title', $workspace->name . ' | بوابة الإدارة')
@section('header', 'تقرير مساحة العمل: ' . $workspace->name)

@section('content')
<div class="card">
    {{-- Quick period shortcuts --}}
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
        @php
            $shortcuts = [
                'اليوم'        => ['from' => now()->startOfDay()->format('Y-m-d'),    'to' => now()->format('Y-m-d')],
                'آخر 7 أيام'  => ['from' => now()->subDays(6)->format('Y-m-d'),       'to' => now()->format('Y-m-d')],
                'هذا الشهر'   => ['from' => now()->startOfMonth()->format('Y-m-d'),   'to' => now()->format('Y-m-d')],
                'الشهر الماضي'=> ['from' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'to' => now()->subMonth()->endOfMonth()->format('Y-m-d')],
            ];
        @endphp
        @foreach($shortcuts as $label => $range)
            <a href="{{ route('admin.workspaces.show', array_merge(['workspace' => $workspace->id], $range)) }}"
               style="padding:6px 14px; border:1px solid var(--upwork-border); border-radius:20px; font-size:13px; font-weight:700; text-decoration:none; color:var(--upwork-slate);
                      {{ $from->format('Y-m-d') === $range['from'] && $to->format('Y-m-d') === $range['to'] ? 'background:var(--upwork-green); color:#fff; border-color:var(--upwork-green);' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.workspaces.show', $workspace) }}">
        <div class="grid-4">
            <div><label>من تاريخ</label><input class="form-input" type="date" name="from" value="{{ $from->format('Y-m-d') }}"></div>
            <div><label>إلى تاريخ</label><input class="form-input" type="date" name="to" value="{{ $to->format('Y-m-d') }}"></div>
            <div>
                <label>نوع الباقة</label>
                <select class="form-select" name="tier">
                    <option value="">كل الباقات</option>
                    @foreach(['FREE' => 'مجاني / دفع مباشر', 'GLOBAL_SUBSCRIPTION' => 'اشتراك التطبيق', 'WORKSPACE_SUBSCRIPTION' => 'اشتراك المساحة'] as $value => $label)
                        <option value="{{ $value }}" @selected($tier === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; align-items:end;"><button class="btn-primary" type="submit" style="width:100%; margin-bottom:16px;">عرض التقرير</button></div>
        </div>
    </form>
</div>

{{-- ── Total highlight ──────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); display:flex; gap:40px; align-items:center; flex-wrap:wrap;">
    <div>
        <div style="color:var(--upwork-muted); font-size:13px; font-weight:700;">إجمالي الفترة</div>
        <div style="font-size:38px; font-weight:900; color:var(--upwork-green);">{{ number_format($summary['total_hours'], 1) }} ساعة</div>
        <div style="font-size:13px; color:var(--upwork-muted);">{{ number_format($summary['total_minutes']) }} دقيقة فعلية</div>
    </div>
    <div style="width:1px; height:60px; background:var(--upwork-border);"></div>
    <div>
        <div style="color:var(--upwork-muted); font-size:13px; font-weight:700;">الزيارات</div>
        <div style="font-size:38px; font-weight:900;">{{ number_format($summary['total_visits']) }}</div>
        <div style="font-size:13px; color:var(--upwork-muted);">{{ number_format($summary['unique_visitors']) }} زائر فريد</div>
    </div>
</div>

{{-- ── Per-tier breakdown ────────────────────────────────────────────── --}}
<div class="grid-4">
    @foreach([
        ['كل الباقات', $summary['total_visits'],   $summary['unique_visitors'],  $summary['total_minutes'],  '#14a800'],
        ['مجاني',      $summary['free_visits'],    $summary['free_visitors'],    $summary['free_minutes'], '#81c784'],
        ['عالمي',      $summary['global_subscription_visits'],  $summary['global_subscription_visitors'],  $summary['global_subscription_minutes'], '#607d8b'],
        ['اشتراك مساحة',       $summary['workspace_subscription_visits'],    $summary['workspace_subscription_visitors'],    $summary['workspace_subscription_minutes'], '#ffb300'],
    ] as [$label, $visitCount, $visitorCount, $minutes, $color])
        <div class="card" style="padding:22px;">
            <div style="color:{{ $color }}; font-weight:800; font-size:14px; margin-bottom:8px;">{{ $label }}</div>
            <div style="font-size:28px; font-weight:900; margin:0 0 4px;">{{ number_format($minutes / 60, 1) }} <small style="font-size:16px; font-weight:600;">ساعة</small></div>
            <div style="font-size:12px; color:var(--upwork-muted);">{{ number_format($visitCount) }} زيارة · {{ number_format($visitorCount) }} زائر</div>
        </div>
    @endforeach
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">تعيين مالك مساحة العمل</h3>
        <p style="color:var(--upwork-muted); margin-bottom:18px;">المالك الحالي: {{ $workspace->owner?->full_name ?? 'دعوة معلقة' }}</p>
        <form action="{{ route('admin.workspaces.owner.change', $workspace) }}" method="POST">
            @csrf
            <label>رقم هاتف المالك الجديد</label>
            <input class="form-input" name="owner_phone" required>
            <label>سبب التغيير</label>
            <input class="form-input" name="reason" maxlength="255" required>
            <button class="btn-primary" type="submit">تغيير المالك</button>
        </form>
    </div>
    <div class="card">
        <h3 class="card-title">تسجيل الفترة كمدفوعة</h3>
        <p style="color:var(--upwork-muted); margin-bottom:18px;">بعد مراجعة الساعات ودفع مستحقات مساحة العمل خارج التطبيق، سجل الفترة كمدفوعة.</p>
        <form action="{{ route('admin.workspaces.settlements.store', $workspace) }}" method="POST" onsubmit="return confirm('تأكيد أن هذه الفترة تم دفعها لمساحة العمل؟');">
            @csrf
            <div class="grid-2">
                <div><label>بداية الفترة</label><input class="form-input" type="date" name="period_started_at" max="{{ $latestPayableDate->format('Y-m-d') }}" value="{{ $paymentFrom->format('Y-m-d') }}" required></div>
                <div><label>نهاية الفترة</label><input class="form-input" type="date" name="period_ended_at" max="{{ $latestPayableDate->format('Y-m-d') }}" value="{{ $paymentTo->format('Y-m-d') }}" required></div>
            </div>
            <label>المبلغ المدفوع بالجنيه</label>
            <input class="form-input" type="number" min="0" step="0.01" name="amount_egp" required>
            <input type="hidden" name="amount_cents" id="amount_cents">
            <label>طريقة الدفع</label>
            <select class="form-select" name="payment_method" required>
                <option value="CASH">نقدي</option>
                <option value="BANK_TRANSFER">تحويل بنكي</option>
                <option value="MOBILE_WALLET">محفظة موبايل خارج التطبيق</option>
                <option value="OTHER">أخرى</option>
            </select>
            <label>مرجع أو ملاحظة الدفع</label>
            <input class="form-input" name="payment_reference" maxlength="255">
            <input class="form-input" name="note" maxlength="255" placeholder="ملاحظة اختيارية">
            <button class="btn-primary" type="submit" @disabled($summary['total_visits'] === 0)>تأكيد دفع الفترة</button>
        </form>
    </div>

    <div class="card">
        <h3 class="card-title">مساحة العمل وQR</h3>
        <p><strong>{{ $workspace->name }}</strong></p>
        <p style="color:var(--upwork-muted); margin-bottom:18px;">{{ $workspace->address }}</p>
        <p style="font-family:monospace; word-break:break-all; background:var(--upwork-bg); padding:12px; border-radius:8px;">{{ $workspace->qr_token }}</p>
        <form action="{{ route('admin.workspaces.qr.regenerate', $workspace) }}" method="POST" style="margin-top:16px;" onsubmit="return confirm('سيتم إلغاء رمز QR السابق. متابعة؟');">
            @csrf
            <button class="btn-primary" type="submit">توليد رمز QR جديد</button>
        </form>
    </div>

    <div class="card">
        <h3 class="card-title">إعدادات الفوترة</h3>
        <p style="color:var(--upwork-muted); margin-bottom:18px;">
            تحكم في كيفية استهلاك دقائق الاشتراك لهذه المساحة.
        </p>
        @if(session('success'))
            <div style="background:#e6f7e6; border:1px solid #14a800; color:#0a5f00; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-weight:600;">
                ✅ {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin.workspaces.billing.update', $workspace) }}" method="POST">
            @csrf
            @method('PATCH')

            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">
                سعر الساعة الأساسي
            </label>
            <input class="form-input" type="number" name="base_hourly_rate_egp" min="0" max="100000" step="0.01"
                   value="{{ old('base_hourly_rate_egp', $workspace->baseHourlyRateEgp()) }}" required>
            <small style="display:block; color:var(--upwork-muted); font-size:12px; margin-bottom:16px;">
                السعر الأساسي بالجنيه المصري قبل تطبيق معامل مساحة العمل.
            </small>
            @error('base_hourly_rate_egp')
                <div style="color:red; font-size:13px; margin-bottom:10px;">{{ $message }}</div>
            @enderror

            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">
                معامل الساعة (hour_multiplier)
            </label>
            <input class="form-input" type="number" name="hour_multiplier" min="0" max="10" step="0.01"
                   value="{{ old('hour_multiplier', $workspace->hour_multiplier ?? 1.0) }}" required>
            <small style="display:block; color:var(--upwork-muted); font-size:12px; margin-bottom:16px;">
                1.0 = قياسي &nbsp;|&nbsp; 2.0 = بريميوم (ضعف استهلاك الوقت) &nbsp;|&nbsp; 0.0 = مجاني
            </small>
            @error('hour_multiplier')
                <div style="color:red; font-size:13px; margin-bottom:10px;">{{ $message }}</div>
            @enderror

            <div style="background:var(--upwork-green-soft); border:1px solid var(--upwork-border); border-radius:10px; padding:14px 16px; margin-bottom:16px;">
                <div style="font-size:12px; color:var(--upwork-muted); font-weight:700;">سعر الساعة الفعلي الحالي</div>
                <div style="font-size:24px; color:var(--upwork-green-dark); font-weight:900; margin-top:4px;">
                    {{ number_format($workspace->effectiveHourlyRateEgp(), 2) }} ج.م / ساعة
                </div>
                <div style="font-size:12px; color:var(--upwork-muted); margin-top:4px;">
                    {{ number_format($workspace->baseHourlyRateEgp(), 2) }} × {{ number_format($workspace->hour_multiplier, 2) }}
                </div>
            </div>

            <label style="font-weight:700; font-size:14px; display:block; margin-bottom:6px;">
                ساعات احتساب اليوم (day_calculation_hours)
            </label>
            <input class="form-input" type="number" name="day_calculation_hours" min="1" max="24"
                   value="{{ old('day_calculation_hours', $workspace->day_calculation_hours ?? 8) }}" required>
            <small style="display:block; color:var(--upwork-muted); font-size:12px; margin-bottom:16px;">
                الحد الأدنى من ساعات الحضور لاحتساب يوم كامل من الاشتراك.
            </small>
            @error('day_calculation_hours')
                <div style="color:red; font-size:13px; margin-bottom:10px;">{{ $message }}</div>
            @enderror

            <button class="btn-primary" type="submit">حفظ إعدادات الفوترة</button>
        </form>
    </div>
</div>

<div class="card" style="padding:0;">
    <div style="padding:24px 24px 0;"><h3 class="card-title">تفاصيل الزيارات</h3></div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>الزائر</th><th>الباقة</th><th>الدخول</th><th>الخروج</th><th>المدة</th><th>تصحيح/استرداد</th></tr></thead>
            <tbody>
                @forelse($visits as $visit)
                    <tr>
                        <td>{{ $visit->visitor_name }}</td>
                        <td>{{ $visit->billing_source?->value ?? $visit->plan_tier_snapshot?->value ?? 'FREE' }}</td>
                        <td>{{ $visit->check_in_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $visit->check_out_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ number_format(($visit->duration_minutes ?? 0) / 60, 2) }} ساعة</td>
                        <td>
                            <form action="{{ route('admin.visits.corrections.store', $visit) }}" method="POST" style="margin-bottom:10px;">
                                @csrf
                                <input class="form-input" type="number" name="minutes_delta" placeholder="+/- دقائق" required>
                                <input class="form-input" name="reason" placeholder="سبب التصحيح" required>
                                <button class="btn-primary" type="submit">تسجيل تصحيح</button>
                            </form>
                            @if($visit->subscription_id)
                                <form action="{{ route('admin.subscriptions.refunds.store', $visit->subscription_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                    <input class="form-input" type="number" min="1" name="refunded_minutes" placeholder="دقائق الاسترداد" required>
                                    <input class="form-input" name="reason" placeholder="سبب الاسترداد" required>
                                    <button class="btn-primary" type="submit">تسجيل استرداد</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center; padding:40px;">لا توجد زيارات مكتملة في الفترة المحددة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 24px;">{{ $visits->links() }}</div>
</div>

<div class="card">
    <h3 class="card-title">الفترات المدفوعة سابقاً</h3>
    <div class="table-responsive">
        <table>
            <thead><tr><th>الفترة</th><th>إجمالي الساعات</th><th>الزيارات</th><th>المبلغ</th><th>تاريخ الدفع</th></tr></thead>
            <tbody>
                @forelse($settlements as $settlement)
                    <tr>
                        <td>{{ $settlement->period_started_at->format('Y-m-d') }} → {{ $settlement->period_ended_at->format('Y-m-d') }}</td>
                        <td>{{ number_format($settlement->total_minutes / 60, 2) }}</td>
                        <td>{{ number_format($settlement->total_visits) }}</td>
                        <td>{{ number_format($settlement->amount_cents / 100, 2) }} {{ $settlement->currency }}</td>
                        <td>
                            {{ $settlement->paid_at->format('Y-m-d H:i') }}
                            @if($settlement->reversal)
                                <strong style="color:var(--upwork-error);">معكوس</strong>
                            @else
                                <form action="{{ route('admin.settlements.reverse', $settlement) }}" method="POST" style="margin-top:8px;">
                                    @csrf
                                    <input class="form-input" name="reason" placeholder="سبب العكس" required>
                                    <button class="btn-primary" type="submit">تسجيل عكس الدفع</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد فترات مدفوعة بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Lifecycle & status controls ─────────────────────────────────── --}}
<div class="card" style="margin-top:24px;">
    <h3 class="card-title">حالة مساحة العمل</h3>
    <p style="color:var(--upwork-muted); margin-bottom:16px;">
        الحالة الحالية:
        <strong>{{ $workspace->lifecycle_status->label() }}</strong>
        @if($workspace->isSuspended() && $workspace->suspension_reason)
            — السبب: {{ $workspace->suspension_reason }}
        @endif
    </p>

    {{-- Operational status + active toggle --}}
    <form action="{{ route('admin.workspaces.status.update', $workspace) }}" method="POST" style="margin-bottom:20px;">
        @csrf
        @method('PATCH')
        <div class="grid-2" style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px; max-width:520px;">
            <div>
                <label>حالة التشغيل</label>
                <select class="form-input" name="status">
                    @foreach(\App\Enums\WorkspaceStatus::cases() as $case)
                        <option value="{{ $case->value }}" @selected($workspace->status === $case)>{{ $case->value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>مفعّلة (ظاهرة للمستخدمين)</label>
                <select class="form-input" name="is_active" @disabled($workspace->isSuspended())>
                    <option value="1" @selected($workspace->is_active)>نعم</option>
                    <option value="0" @selected(!$workspace->is_active)>لا</option>
                </select>
            </div>
        </div>
        <button class="btn-primary" type="submit" style="margin-top:12px;">حفظ الحالة</button>
        @if($workspace->isSuspended())
            <span style="color:var(--upwork-muted); font-size:13px; margin-right:10px;">مساحة العمل موقوفة — ألغِ الإيقاف لإعادة تفعيلها.</span>
        @endif
    </form>

    {{-- Freeze / unfreeze --}}
    @if($workspace->isSuspended())
        <form action="{{ route('admin.workspaces.unsuspend', $workspace) }}" method="POST">
            @csrf
            <button class="btn-primary" type="submit" style="background:var(--upwork-green);">إلغاء الإيقاف وإعادة التفعيل</button>
        </form>
    @else
        <form action="{{ route('admin.workspaces.suspend', $workspace) }}" method="POST"
              onsubmit="return confirm('سيتم إيقاف مساحة العمل مؤقتًا وإخفاؤها عن المستخدمين. متابعة؟');"
              style="display:flex; gap:8px; align-items:center; max-width:520px;">
            @csrf
            <input class="form-input" name="reason" maxlength="255" placeholder="سبب الإيقاف (اختياري)" style="flex:1;">
            <button type="submit" style="background:#b76e00; color:#fff; border:none; padding:11px 20px; border-radius:var(--radius-sm); font-weight:700; cursor:pointer; white-space:nowrap;">إيقاف مؤقت (تجميد)</button>
        </form>
    @endif
</div>

{{-- ── Danger zone: delete ─────────────────────────────────────────── --}}
<div class="card" style="margin-top:24px; border:1px solid var(--upwork-error);">
    <h3 class="card-title" style="color:var(--upwork-error);">حذف مساحة العمل</h3>

    @error('delete')
        <div style="color:var(--upwork-error); font-weight:700; margin-bottom:12px;">{{ $message }}</div>
    @enderror

    <p style="color:var(--upwork-muted); margin-bottom:12px;">
        الحذف يخفي مساحة العمل عن التطبيق مع الاحتفاظ بكامل سجل الزيارات والمدفوعات (يمكن استعادتها لاحقًا).
    </p>

    @if($activeVisitsCount > 0)
        <div style="color:var(--upwork-error); font-weight:700; margin-bottom:12px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            لا يمكن الحذف: يوجد {{ $activeVisitsCount }} زائر مسجّل دخول حاليًا. يجب تسجيل خروجهم أولاً.
        </div>
    @endif

    @if($unsettled['visits'] > 0)
        <div style="background:#fff7e6; color:#b76e00; padding:12px 16px; border-radius:var(--radius-sm); margin-bottom:12px; font-weight:600;">
            <i class="fa-solid fa-circle-exclamation"></i>
            تحذير: يوجد مستحقات غير مسوّاة ({{ $unsettled['hours'] }} ساعة ≈ {{ number_format($unsettled['amount_egp'], 2) }} ج.م) منذ آخر تسوية. يُفضّل تسويتها قبل الحذف.
        </div>
    @endif

    <form action="{{ route('admin.workspaces.destroy', $workspace) }}" method="POST"
          onsubmit="return confirm('سيتم حذف مساحة العمل. هل أنت متأكد؟');"
          style="display:flex; gap:8px; align-items:center; max-width:520px;">
        @csrf
        @method('DELETE')
        <input class="form-input" name="reason" maxlength="255" required placeholder="سبب الحذف" style="flex:1;" @disabled($activeVisitsCount > 0)>
        <button type="submit" @disabled($activeVisitsCount > 0)
                style="background:var(--upwork-error); color:#fff; border:none; padding:11px 20px; border-radius:var(--radius-sm); font-weight:700; cursor:{{ $activeVisitsCount > 0 ? 'not-allowed' : 'pointer' }}; white-space:nowrap; opacity:{{ $activeVisitsCount > 0 ? '0.5' : '1' }};">
            حذف نهائي
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.querySelector('form[action*="/settlements"]').addEventListener('submit', function () {
    const amount = Number(this.querySelector('[name="amount_egp"]').value || 0);
    this.querySelector('[name="amount_cents"]').value = Math.round(amount * 100);
});
</script>
@endsection
