@extends('workspace.layouts.app')

@section('title', $session->title.' | جلسة خاصة')

@section('content')
<div class="private-session-page">
    <a href="{{ route('workspace.private-sessions.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">→ رجوع للجلسات الخاصة</a>
    <div class="private-session-hero">
        <div>
            <h1 class="private-session-title">{{ $session->title }}</h1>
            <p class="private-session-subtitle">{{ $session->starts_at->format('Y/m/d H:i') }} · {{ number_format($session->priceEgp(), 2) }} ج.م للفرد</p>
        </div>
        <div class="private-session-actions">
            <a href="{{ route('workspace.private-sessions.qr', $session) }}" class="btn-primary private-session-action-main" style="text-decoration:none;"><i class="fa-solid fa-qrcode"></i> QR الجلسة</a>
            @if($session->status === 'active')
                <form action="{{ route('workspace.private-sessions.finish', $session) }}" method="POST" class="session-action-wrap">
                    @csrf
                    <button class="session-action-btn session-action-finish" type="submit" onclick="return confirm('إنهاء الجلسة سيغلق تسجيل QR ويحفظ الحضور والإيراد كما هو. هل تريد المتابعة؟');">
                        <i class="fa-solid fa-circle-check"></i>
                        إنهاء الجلسة
                    </button>
                    <span class="session-action-tooltip">إنهاء الجلسة: استخدمه عندما تكون الجلسة انعقدت بالفعل وانتهت الآن. سيغلق تسجيل الحضور بالـ QR، ويحتفظ بالحضور والإيراد كأرقام نهائية حقيقية.</span>
                </form>
                <form action="{{ route('workspace.private-sessions.cancel', $session) }}" method="POST" class="session-action-wrap" onsubmit="return confirm('إلغاء الجلسة سيغلق QR ويضع الجلسة كملغية. استخدمه إذا لم تُعقد الجلسة. هل تريد المتابعة؟');">
                    @csrf
                    <button class="session-action-btn session-action-cancel" type="submit">
                        <i class="fa-solid fa-ban"></i>
                        إلغاء الجلسة
                    </button>
                    <span class="session-action-tooltip">إلغاء الجلسة: استخدمه إذا لم تُعقد الجلسة أو تم إلغاؤها. سيغلق تسجيل الحضور بالـ QR أيضاً، لكنه يضع حالة الجلسة كملغية.</span>
                </form>
            @endif
            <small class="session-action-hint">إنهاء = الجلسة تمت وانتهت. إلغاء = الجلسة لم تُعقد أو تم إلغاؤها.</small>
        </div>
    </div>

    @if(session('import_errors'))
        <div class="card private-session-dark-card" style="margin-bottom:16px; border-right:4px solid #f87171;">
            <strong>ملاحظات الاستيراد:</strong>
            <ul style="margin:8px 0 0;">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid-4 private-session-summary-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px;">
        <div class="card private-session-stat"><small>المضافين</small><h2 style="margin:8px 0 0;">{{ $summary['invited'] }}</h2></div>
        <div class="card private-session-stat"><small>حضروا</small><h2 style="margin:8px 0 0;">{{ $summary['attended'] }}</h2></div>
        <div class="card private-session-stat"><small>إيراد فعلي</small><h2 style="margin:8px 0 0;">{{ number_format($summary['actual_revenue_cents'] / 100, 2) }} ج.م</h2></div>
        <div class="card private-session-stat"><small>QR / يدوي</small><h2 style="margin:8px 0 0;">{{ $summary['qr_checkins'] }} / {{ $summary['owner_checkins'] }}</h2></div>
    </div>

    <div class="manage-grid" style="margin-bottom:22px;">
        <div class="manage-main card private-session-dark-card">
            <h3 style="margin:0 0 14px;">إضافة زائر يدويًا</h3>
            <form action="{{ route('workspace.private-sessions.attendees.store', $session) }}" method="POST">
                @csrf
                <div class="grid-2" style="gap:12px;">
                    <div>
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" required>
                    </div>
                    <div>
                        <label>الاسم إذا لم يكن مستخدم تطبيق</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                <label style="display:flex; gap:8px; align-items:center; margin-top:12px; font-weight:700;">
                    <input type="checkbox" name="check_in_now" value="1" id="private-session-check-in-now">
                    إضافة وتسجيل حضور الآن
                </label>
                <button type="submit" class="btn-primary" style="margin-top:12px;">إضافة للجلسة</button>
            </form>
        </div>
        <aside class="manage-aside card private-session-dark-card">
            <h3 style="margin:0 0 14px;">استيراد CSV</h3>
            <p style="color:var(--upwork-muted); font-size:13px;">الملف يقبل CSV أو XLSX بأعمدة: phone,name. إذا كان الرقم غير مسجل في التطبيق فالاسم مطلوب.</p>
            <form action="{{ route('workspace.private-sessions.attendees.import', $session) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="attendees_file" class="form-control" accept=".csv,.xlsx,text/csv,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                <button type="submit" class="btn-primary" style="margin-top:12px;">استيراد الحضور</button>
            </form>
        </aside>
    </div>

    <div class="card private-session-dark-card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 14px;">الزوار الذين حضروا</h3>
        @php $attendedRows = $session->attendees->where('status', 'attended'); @endphp
        @if($attendedRows->isEmpty())
            <p class="empty-state">لا يوجد حضور مسجل بعد.</p>
        @else
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead><tr style="border-bottom:2px solid var(--upwork-border);"><th style="padding:10px;">الزائر</th><th style="padding:10px;">الهاتف</th><th style="padding:10px;">النوع</th><th style="padding:10px;">وقت الدخول</th><th style="padding:10px;">الطريقة</th><th style="padding:10px;">المبلغ</th></tr></thead>
                    <tbody>
                        @foreach($attendedRows as $attendee)
                            <tr style="border-bottom:1px solid var(--upwork-border);">
                                <td style="padding:10px; font-weight:700;">{{ $attendee->name_snapshot }}</td>
                                <td style="padding:10px; direction:ltr; text-align:right;">{{ $attendee->phone_snapshot }}</td>
                                <td style="padding:10px;">{{ $attendee->user_id ? 'مستخدم تطبيق' : 'Walk-in' }}</td>
                                <td style="padding:10px;"><small>{{ $attendee->checked_in_at?->format('Y/m/d H:i') }}</small></td>
                                <td style="padding:10px;">{{ $attendee->checked_in_method === 'qr' ? 'QR' : 'المالك' }}</td>
                                <td style="padding:10px; color:var(--upwork-green-dark); font-weight:700;">{{ number_format($attendee->amountEgp(), 2) }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card private-session-dark-card">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
            <h3 style="margin:0;">كل المسجلين للجلسة</h3>
            @if($session->attendees->isNotEmpty())
                <div style="min-width:260px; max-width:420px; flex:1;">
                    <label for="private-session-attendee-search" style="font-size:12px; color:var(--upwork-muted);">بحث بالاسم أو رقم الهاتف</label>
                    <input id="private-session-attendee-search" type="search" class="form-control" placeholder="اكتب رقم الهاتف أو الاسم..." style="margin-top:4px;">
                </div>
            @endif
        </div>
        @if($session->attendees->isEmpty())
            <p class="empty-state">لم يتم إضافة زوار بعد.</p>
        @else
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead><tr style="border-bottom:2px solid var(--upwork-border);"><th style="padding:10px;">الاسم</th><th style="padding:10px;">الهاتف</th><th style="padding:10px;">المصدر</th><th style="padding:10px;">الحالة</th><th style="padding:10px;">إجراء</th></tr></thead>
                    <tbody id="private-session-attendees-body">
                        @foreach($session->attendees as $attendee)
                            <tr data-attendee-row data-search="{{ strtolower($attendee->name_snapshot.' '.$attendee->phone_snapshot.' '.$attendee->phone_normalized) }}" style="border-bottom:1px solid var(--upwork-border);">
                                <td style="padding:10px; font-weight:700;">{{ $attendee->name_snapshot }}</td>
                                <td style="padding:10px; direction:ltr; text-align:right;">{{ $attendee->phone_snapshot }}</td>
                                <td style="padding:10px;">{{ $attendee->source }}</td>
                                <td style="padding:10px;">{{ $attendee->status === 'attended' ? 'حضر' : 'منتظر' }}</td>
                                <td style="padding:10px;">
                                    @if($attendee->status !== 'attended')
                                        <form action="{{ route('workspace.private-sessions.attendees.check-in', [$session, $attendee]) }}" method="POST">@csrf<button class="btn-primary" type="submit" style="box-shadow:none; padding:7px 12px;">تسجيل حضور</button></form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr id="private-session-attendees-empty-search" style="display:none;">
                            <td colspan="5" class="empty-state">لا يوجد مسجل بهذا الاسم أو الرقم.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
            linear-gradient(135deg, #16211b 0%, #1d2c24 100%);
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
        background: #21322a;
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
        const rows = Array.from(document.querySelectorAll('[data-attendee-row]'));
        const emptyRow = document.getElementById('private-session-attendees-empty-search');

        if (search) {
            const normalize = function (value) {
                return String(value || '').toLowerCase().replace(/[^\d\p{L}\s]/gu, '').trim();
            };

            const filterRows = function () {
                const query = normalize(search.value);
                let visible = 0;

                rows.forEach(function (row) {
                    const haystack = normalize(row.dataset.search);
                    const match = query === '' || haystack.includes(query);
                    row.style.display = match ? '' : 'none';
                    if (match) {
                        visible++;
                    }
                });

                if (emptyRow) {
                    emptyRow.style.display = visible === 0 ? '' : 'none';
                }
            };

            search.addEventListener('input', filterRows);
            search.addEventListener('change', filterRows);
        }
    })();
</script>
@endsection
