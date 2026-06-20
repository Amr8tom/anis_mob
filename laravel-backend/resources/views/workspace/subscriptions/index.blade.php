@extends('workspace.layouts.app')

@section('title', 'الاشتراكات الخاصة | بوابة مساحة العمل')

@section('content')
<div class="workspace-dark-page workspace-subscriptions-dark">
    <div class="workspace-dark-hero">
        <div>
            <h1 class="workspace-dark-title">الاشتراكات الخاصة بمساحتك</h1>
            <p class="workspace-dark-subtitle">باقات تصدرها أنت، وتصلح للحضور في مساحتك فقط بنفس رمز الـ QR الحالي.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="card workspace-dark-card" style="margin-bottom:16px; border-right:4px solid #2bd968;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="card workspace-dark-card" style="margin-bottom:16px; border-right:4px solid #f87171;">{{ $errors->first() }}</div>
    @endif
    @if(session('generated_workspace_codes'))
        <div class="card workspace-dark-card" style="margin-bottom:16px; border-right:4px solid #2bd968;">
            <h3 style="margin-top:0;">أكواد التفعيل الجديدة</h3>
            @foreach(session('generated_workspace_codes') as $generatedCode)
                <code style="display:inline-block; direction:ltr; padding:8px 12px; margin:4px; background:var(--upwork-bg); border-radius:6px;">{{ $generatedCode }}</code>
            @endforeach
        </div>
    @endif

    {{-- ── Metrics ───────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
        <div class="card workspace-dark-stat" style="text-align:center; padding:18px;">
            <div style="font-size:28px; font-weight:900; color:var(--upwork-green);">{{ number_format($metrics['active_count']) }}</div>
            <div style="font-size:13px; color:var(--upwork-muted);">اشتراكات فعّالة</div>
        </div>
        <div class="card workspace-dark-stat" style="text-align:center; padding:18px; {{ $metrics['expiring_count'] > 0 ? 'border-right:4px solid #f4a800;' : '' }}">
            <div style="font-size:28px; font-weight:900; color:#8a6413;">{{ number_format($metrics['expiring_count']) }}</div>
            <div style="font-size:13px; color:var(--upwork-muted);">تنتهي خلال 3 أيام</div>
        </div>
        <div class="card workspace-dark-stat" style="text-align:center; padding:18px;">
            <div style="font-size:28px; font-weight:900; color:var(--upwork-slate);">{{ number_format($metrics['hours_sold'], 1) }}</div>
            <div style="font-size:13px; color:var(--upwork-muted);">ساعات مُباعة</div>
        </div>
        <div class="card workspace-dark-stat" style="text-align:center; padding:18px;">
            <div style="font-size:28px; font-weight:900; color:var(--upwork-slate);">{{ number_format($metrics['plan_count']) }}</div>
            <div style="font-size:13px; color:var(--upwork-muted);">باقات</div>
        </div>
    </div>

    {{-- ── Expiring soon ─────────────────────────────────────── --}}
    @if($expiring_soon->isNotEmpty())
        <div class="card workspace-dark-card" style="margin-bottom:22px; border:1px solid #f4c775;">
            <h3 style="margin:0 0 4px; color:#8a6413;"><i class="fa-solid fa-bell"></i> اشتراكات تنتهي قريباً</h3>
            <p style="margin:0 0 14px; color:var(--upwork-muted); font-size:13px;">ذكّر الزائر بالتجديد قبل انتهاء اشتراكه في مساحتك.</p>
            @foreach($expiring_soon as $sub)
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 12px; background:#fff8e6; border-radius:var(--radius-sm); margin-bottom:8px;">
                    <span style="font-weight:700; color:#8a6413;">{{ $sub->subscriberName() }} · <span style="direction:ltr;">{{ $sub->subscriberPhone() ?? '—' }}</span></span>
                    <span style="font-size:13px; color:#8a6413;">يبقى {{ $sub->daysLeft() }} يوم · {{ number_format($sub->remaining_minutes / 60, 1) }} ساعة</span>
                    @if($sub->user)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sub->user->whatsapp_number) }}" target="_blank"
                           style="padding:6px 12px; border-radius:var(--radius-sm); border:1px solid var(--upwork-green); color:var(--upwork-green-dark); text-decoration:none; font-weight:700; font-size:13px;">
                            <i class="fa-brands fa-whatsapp"></i> تذكير
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Issue a subscription ──────────────────────────────── --}}
    <div class="card workspace-dark-card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 16px;"><i class="fa-solid fa-paper-plane"></i> إصدار اشتراك لزائر</h3>
        @php $activePlans = $plans->where('is_active', true); @endphp
        @if($activePlans->isEmpty())
            <p style="color:var(--upwork-muted);">أنشئ باقة أولاً من القسم بالأسفل لتتمكن من إصدار اشتراكات.</p>
        @else
            <div class="grid-2" style="gap:18px;">
                {{-- Generate code --}}
                <form action="{{ route('workspace.subscriptions.codes.generate') }}" method="POST" style="background:var(--upwork-bg); padding:16px; border-radius:var(--radius-sm); border:1px solid var(--upwork-border);">
                    @csrf
                    <div style="font-weight:800; margin-bottom:10px;">توليد كود تفعيل</div>
                    <label style="font-size:13px;">الباقة</label>
                    <select name="workspace_plan_id" class="form-control" required>
                        @foreach($activePlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} — {{ (int)($plan->included_minutes/60) }} ساعة / {{ $plan->duration_days }} يوم</option>
                        @endforeach
                    </select>
                    <div class="grid-2" style="gap:10px; margin-top:10px;">
                        <div><label style="font-size:13px;">عدد الأكواد</label><input type="number" name="count" class="form-control" value="1" min="1" max="200" required></div>
                        <div><label style="font-size:13px;">صلاحية الكود (أيام)</label><input type="number" name="expires_in_days" class="form-control" value="30" min="1" max="365"></div>
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top:12px;"><i class="fa-solid fa-ticket"></i> توليد</button>
                </form>

                {{-- Assign by phone (app user OR walk-in) --}}
                <form action="{{ route('workspace.subscriptions.assign') }}" method="POST" style="background:var(--upwork-bg); padding:16px; border-radius:var(--radius-sm); border:1px solid var(--upwork-border);">
                    @csrf
                    <div style="font-weight:800; margin-bottom:4px;">ربط برقم هاتف</div>
                    <p style="font-size:12px; color:var(--upwork-muted); margin:0 0 10px;">إن لم يكن الرقم لمستخدم مسجّل بالتطبيق، سيُسجَّل كزائر مباشر تلقائياً ثم تُفعّل له الباقة.</p>
                    <label style="font-size:13px;">الباقة</label>
                    <select name="workspace_plan_id" class="form-control" required>
                        @foreach($activePlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} — {{ (int)($plan->included_minutes/60) }} ساعة / {{ $plan->duration_days }} يوم</option>
                        @endforeach
                    </select>
                    <label style="font-size:13px; margin-top:10px; display:block;">رقم هاتف الزائر</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" style="direction:ltr; text-align:right;" value="{{ old('phone_number') }}" required>
                    <label style="font-size:13px; margin-top:10px; display:block;">الاسم (لزائر جديد فقط)</label>
                    <input type="text" name="name" class="form-control" placeholder="اسم الزائر" value="{{ old('name') }}">
                    <button type="submit" class="btn-primary" style="margin-top:12px;"><i class="fa-solid fa-user-check"></i> تفعيل مباشر</button>
                </form>
            </div>
        @endif
    </div>

    <div class="card workspace-dark-card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 16px;">أكواد التفعيل</h3>
        <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr><th style="padding:10px;">الكود</th><th style="padding:10px;">الباقة</th><th style="padding:10px;">الحالة</th><th style="padding:10px;">الصلاحية</th><th style="padding:10px;">إجراء</th></tr></thead>
                <tbody>
                    @forelse($codes as $code)
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px;"><code style="direction:ltr;">{{ $code->code }}</code></td>
                            <td style="padding:10px;">{{ $code->plan?->name ?? '—' }}</td>
                            <td style="padding:10px;">{{ $code->status->value }}</td>
                            <td style="padding:10px;">{{ $code->expires_at?->format('Y-m-d') ?? 'بدون انتهاء' }}</td>
                            <td style="padding:10px;">
                                @if($code->status->value === 'UNUSED')
                                    <form action="{{ route('workspace.subscriptions.codes.revoke', $code->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="padding:6px 12px; border:1px solid var(--upwork-border); background:#fff; border-radius:var(--radius-sm); cursor:pointer; color:var(--upwork-error);">إلغاء</button>
                                    </form>
                                @else — @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:16px; text-align:center; color:var(--upwork-muted);">لا توجد أكواد بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $codes->links('vendor.pagination.upwork') }}</div>
    </div>

    {{-- ── Plan templates ────────────────────────────────────── --}}
    <div class="card workspace-dark-card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 18px;"><i class="fa-solid fa-layer-group"></i> باقاتك</h3>
        <div class="manage-grid">
            {{-- List (primary) --}}
            <div class="manage-main">
                @if($plans->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-layer-group"></i>
                        لا توجد باقات بعد. أنشئ أول باقة من النموذج المجاور لتتمكن من إصدار اشتراكات.
                    </div>
                @else
                    <table>
                        <thead><tr><th>الباقة</th><th>الساعات</th><th>الأيام</th><th>السعر</th><th>الحالة</th><th>إجراء</th></tr></thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr>
                                    <td style="font-weight:700;">{{ $plan->name }}</td>
                                    <td>{{ (int)($plan->included_minutes/60) }}</td>
                                    <td>{{ $plan->duration_days }}</td>
                                    <td>{{ $plan->price_cents ? number_format($plan->price_cents/100, 2).' ج.م' : '—' }}</td>
                                    <td>
                                        @if($plan->is_active)
                                            <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">فعّالة</span>
                                        @else
                                            <span style="background:#eee; color:#777; padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">موقوفة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($plan->is_active)
                                            <form action="{{ route('workspace.subscriptions.plans.deactivate', $plan->id) }}" method="POST" onsubmit="return confirm('إيقاف هذه الباقة؟ لن تتأثر الاشتراكات الصادرة.');">
                                                @csrf
                                                <button type="submit" style="padding:6px 12px; border:1px solid var(--upwork-border); background:#fff; border-radius:var(--radius-sm); cursor:pointer; font-weight:700; color:var(--upwork-error);">إيقاف</button>
                                            </form>
                                        @else — @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Add form (side) --}}
            <aside class="manage-aside">
                <form action="{{ route('workspace.subscriptions.plans.store') }}" method="POST" class="manage-form-panel">
                    @csrf
                    <h4><i class="fa-solid fa-plus" style="color:var(--upwork-green);"></i> إضافة باقة</h4>
                    <label style="font-size:13px;">اسم الباقة</label>
                    <input type="text" name="name" class="form-control" placeholder="باقة المذاكرة" required>
                    <label style="font-size:13px;">عدد الساعات</label>
                    <input type="number" name="hours" class="form-control" min="1" max="1000" value="20" required>
                    <label style="font-size:13px;">عدد الأيام</label>
                    <input type="number" name="duration_days" class="form-control" min="1" max="365" value="30" required>
                    <label style="font-size:13px;">السعر (ج.م)</label>
                    <input type="number" name="price_pounds" class="form-control" min="0" step="0.01" placeholder="اختياري">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-plus"></i> إضافة باقة</button>
                </form>
            </aside>
        </div>
    </div>

    {{-- ── Issued subscriptions ──────────────────────────────── --}}
    <div class="card workspace-dark-card">
        <h3 style="margin:0 0 16px;">الاشتراكات الصادرة</h3>
        @if($subscriptions->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:16px;">لم تصدر أي اشتراكات بعد.</p>
        @else
            <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:10px;">الزائر</th><th style="padding:10px;">الباقة</th><th style="padding:10px;">ساعات متبقية</th><th style="padding:10px;">أيام متبقية</th><th style="padding:10px;">الحالة</th><th style="padding:10px;">إجراء</th>
                </tr></thead>
                <tbody>
                    @foreach($subscriptions as $sub)
                        @php
                            $statusMap = [
                                'ACTIVE' => ['فعّال', 'var(--upwork-green-soft)', 'var(--upwork-green-dark)'],
                                'EXHAUSTED' => ['مستهلك', '#fff3d6', '#8a6413'],
                                'EXPIRED' => ['منتهٍ', '#eee', '#777'],
                                'CANCELLED' => ['ملغي', '#fdecec', '#b91c1c'],
                            ];
                            $s = $statusMap[$sub->status->value] ?? ['—', '#eee', '#777'];
                        @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px; font-weight:700;">
                                {{ $sub->subscriberName() }}
                                @if($sub->walk_in_id)<span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:1px 7px; border-radius:20px; font-size:10px; font-weight:800; margin-inline-start:4px;">زائر مباشر</span>@endif
                                <br><small style="color:var(--upwork-muted); direction:ltr;">{{ $sub->subscriberPhone() ?? '' }}</small>
                            </td>
                            <td style="padding:10px;">{{ $sub->plan_name_snapshot }}</td>
                            <td style="padding:10px;">{{ number_format($sub->remaining_minutes/60, 1) }} / {{ number_format($sub->total_minutes/60, 1) }}</td>
                            <td style="padding:10px;">{{ $sub->daysLeft() }}</td>
                            <td style="padding:10px;"><span style="background:{{ $s[1] }}; color:{{ $s[2] }}; padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">{{ $s[0] }}</span></td>
                            <td style="padding:10px;">
                                @if($sub->status->value === 'ACTIVE')
                                    <form action="{{ route('workspace.subscriptions.cancel', $sub->id) }}" method="POST" onsubmit="return confirm('إلغاء اشتراك هذا الزائر؟');">
                                        @csrf
                                        <button type="submit" style="padding:6px 12px; border:1px solid var(--upwork-border); background:#fff; border-radius:var(--radius-sm); cursor:pointer; font-weight:700; color:var(--upwork-error);">إلغاء</button>
                                    </form>
                                @else — @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div style="margin-top:16px;">{{ $subscriptions->links('vendor.pagination.upwork') }}</div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .workspace-dark-page {
        --wd-surface: #16211b;
        --wd-surface-2: #1d2c24;
        --wd-input: #0f1813;
        --wd-border: #2c3d33;
        --wd-text: #eef3f0;
        --wd-muted: #9db0a4;
        --wd-dim: #6c7e73;
        --wd-accent: #2bd968;
    }

    .workspace-dark-hero {
        margin-bottom: 22px;
        padding: 24px;
        border-radius: var(--radius-md);
        border: 1px solid var(--wd-border);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .13), transparent 28%),
            linear-gradient(135deg, #16211b 0%, #1d2c24 100%);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .workspace-dark-title {
        margin: 0 0 8px;
        color: var(--wd-text);
        font-size: 34px;
        line-height: 1.2;
        font-weight: 900;
    }

    .workspace-dark-subtitle {
        margin: 0;
        color: var(--wd-muted);
        font-weight: 700;
    }

    .workspace-dark-page .card,
    .workspace-dark-page .workspace-dark-card,
    .workspace-dark-page .workspace-dark-stat,
    .workspace-dark-page .manage-form-panel {
        background: var(--wd-surface) !important;
        color: var(--wd-text) !important;
        border: 1px solid var(--wd-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .workspace-dark-page h3,
    .workspace-dark-page h4,
    .workspace-dark-page label,
    .workspace-dark-page td {
        color: var(--wd-text) !important;
    }

    .workspace-dark-page p,
    .workspace-dark-page small,
    .workspace-dark-page .empty-state,
    .workspace-dark-page .workspace-dark-stat div:last-child {
        color: var(--wd-muted) !important;
    }

    .workspace-dark-page .workspace-dark-stat div:first-child,
    .workspace-dark-page td[style*="var(--upwork-green-dark)"] {
        color: var(--wd-accent) !important;
    }

    .workspace-dark-page .form-control,
    .workspace-dark-page select,
    .workspace-dark-page input,
    .workspace-dark-page textarea {
        background: var(--wd-input) !important;
        border-color: var(--wd-border) !important;
        color: var(--wd-text) !important;
    }

    .workspace-dark-page .form-control::placeholder {
        color: var(--wd-dim);
    }

    .workspace-dark-page form[style*="var(--upwork-bg)"],
    .workspace-dark-page code[style*="var(--upwork-bg)"] {
        background: var(--wd-input) !important;
        border-color: var(--wd-border) !important;
        color: var(--wd-text) !important;
    }

    .workspace-dark-page table {
        color: var(--wd-text);
    }

    .workspace-dark-page thead tr {
        background: var(--wd-surface-2) !important;
        border-bottom: 2px solid var(--wd-border) !important;
    }

    .workspace-dark-page tbody tr {
        border-bottom: 1px solid var(--wd-border) !important;
    }

    .workspace-dark-page tbody tr:hover {
        background: #21322a;
    }

    .workspace-dark-page th {
        color: var(--wd-muted) !important;
    }

    .workspace-dark-page button[style*="background:#fff"],
    .workspace-dark-page button[style*="background: #fff"] {
        background: var(--wd-input) !important;
        border-color: var(--wd-border) !important;
    }
</style>
@endsection
