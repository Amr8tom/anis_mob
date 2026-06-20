@extends('workspace.layouts.app')

@section('title', 'حجوزات الغرف | بوابة مساحة العمل')

@section('styles')
@include('workspace.partials.dark-theme')
@endsection

@section('content')
<div class="visits-dark">
    <h1 class="page-title" style="margin:0 0 6px;">حجوزات الغرف</h1>
    <p class="page-subtitle">سجّل حجوزات غرفك بسرعة للزائر العادي أو مستخدم التطبيق. اكتب رقم الهاتف فقط للعميل المحفوظ، أو أضف الاسم مرة واحدة للزائر الجديد.</p>

    @if(session('success'))
        <div class="vd-card vd-card--pad" style="margin-bottom:16px; border-right:4px solid var(--vd-accent); color:var(--vd-accent);">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="vd-card vd-card--pad" style="margin-bottom:16px; border-right:4px solid var(--vd-danger); color:#ff7b78;">{{ $errors->first() }}</div>
    @endif

    {{-- ── New reservation ───────────────────────────────────── --}}
    <div class="vd-card vd-card--accent">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-calendar-plus"></i></div>
                <div>
                    <h3>حجز غرفة</h3>
                    <p>أنشئ حجزاً جديداً لإحدى غرفك.</p>
                </div>
            </div>
        </div>
        <div class="vd-body">
        @if($activeRooms->isEmpty())
            <p style="color:var(--vd-text-muted); margin:0;">أضف غرفة أولاً من قسم «غرفك» بالأسفل لتتمكن من الحجز.</p>
        @else
            <form action="{{ route('workspace.rooms.reservations.store') }}" method="POST">
                @csrf
                <div class="grid-3" style="gap:14px;">
                    <div>
                        <label style="font-size:13px;">الغرفة</label>
                        <select name="room_id" class="form-control" required>
                            @foreach($activeRooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} — {{ number_format($room->hourlyPriceEgp(), 2) }} ج.م/ساعة{{ $room->note ? ' — '.$room->note : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:13px;">اسم العميل أو الزائر</label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" list="room-client-names" placeholder="اختياري إذا كان الهاتف محفوظاً">
                    </div>
                    <div>
                        <label style="font-size:13px;">رقم هاتف العميل</label>
                        <input type="text" name="client_phone" class="form-control" style="direction:ltr; text-align:right;" placeholder="01xxxxxxxxx" value="{{ old('client_phone') }}" list="room-client-phones" required>
                        <small style="color:var(--vd-text-dim); font-size:11px;">يدعم زائر walk-in أو مستخدم تطبيق. لو الرقم محفوظ، سيتم ملء الاسم تلقائياً.</small>
                    </div>
                </div>
                <datalist id="room-client-names">
                    @foreach($clients as $c)
                        <option value="{{ $c->name }}">{{ $c->phone }}</option>
                    @endforeach
                </datalist>
                <datalist id="room-client-phones">
                    @foreach($clients as $c)
                        <option value="{{ $c->phone }}">{{ $c->name }}</option>
                    @endforeach
                </datalist>
                <div class="grid-3" style="gap:14px; margin-top:12px;">
                    <div>
                        <label style="font-size:13px;">التاريخ</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
                    </div>
                    <div>
                        <label style="font-size:13px;">وقت البدء</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time', '16:00') }}" required>
                    </div>
                    <div>
                        <label style="font-size:13px;">المدة</label>
                        <select name="duration_minutes" class="form-control" required>
                            @foreach([30=>'٣٠ دقيقة',60=>'ساعة',90=>'ساعة ونصف',120=>'ساعتان',180=>'٣ ساعات',240=>'٤ ساعات'] as $min=>$label)
                                <option value="{{ $min }}" {{ (int)old('duration_minutes',60)===$min ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Recurrence --}}
                <div class="vd-softbox" style="margin-top:14px; padding:14px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:700; color:var(--vd-text);">
                        <input type="checkbox" name="recurring" value="1" id="recurring-toggle" onchange="document.getElementById('recurrence-options').style.display=this.checked?'block':'none'">
                        تكرار أسبوعي
                    </label>
                    <div id="recurrence-options" style="display:none; margin-top:12px;">
                        <label style="font-size:13px; display:block; margin-bottom:6px;">أيام التكرار</label>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            @foreach([0=>'الأحد',1=>'الإثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت'] as $d=>$label)
                                <label class="vd-chip" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; cursor:pointer; font-size:13px;">
                                    <input type="checkbox" name="weekdays[]" value="{{ $d }}"> {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <label style="font-size:13px; display:block; margin-top:10px;">حتى تاريخ (اختياري، بحد أقصى ١٢ أسبوعاً)</label>
                        <input type="date" name="until" class="form-control" style="max-width:240px;">
                        <small style="color:var(--vd-text-dim); font-size:11px; display:block; margin-top:4px;">المواعيد المتعارضة مع حجوزات قائمة سيتم تخطّيها وإبلاغك بها.</small>
                    </div>
                </div>

                <div class="grid-2" style="gap:14px; margin-top:12px;">
                    <div>
                        <label style="font-size:13px;">ملاحظة على العميل</label>
                        <textarea name="client_note" class="form-control" rows="2" placeholder="مثلاً: يفضّل غرفة هادئة">{{ old('client_note') }}</textarea>
                    </div>
                    <div>
                        <label style="font-size:13px;">ملاحظة على الحجز</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="مثلاً: يحتاج شاشة أو سبورة">{{ old('note') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="margin-top:14px;"><i class="fa-solid fa-check"></i> تأكيد الحجز</button>
            </form>
        @endif
        </div>
    </div>

    {{-- ── Upcoming reservations ─────────────────────────────── --}}
    <div class="vd-card">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div><h3>الحجوزات القادمة</h3><p>المواعيد المحجوزة القادمة في غرفك.</p></div>
            </div>
        </div>
        <div class="vd-body">
        @if($upcoming->isEmpty())
            <div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i> لا توجد حجوزات قادمة.</div>
        @else
            <div class="table-responsive">
            <table>
                <thead><tr>
                    <th>الغرفة</th><th>العميل</th><th>من</th><th>إلى</th><th>المدة</th><th>التكلفة</th><th>ملاحظات</th><th>إجراء</th>
                </tr></thead>
                <tbody>
                    @foreach($upcoming as $r)
                        @php $mins=$r->durationMinutes(); $h=intdiv($mins,60); $m=$mins%60; @endphp
                        <tr>
                            <td style="font-weight:700;">
                                {{ $r->room->name ?? '—' }}
                                @if($r->room?->note)<br><small class="sub">{{ $r->room->note }}</small>@endif
                            </td>
                            <td>
                                {{ $r->client_name }}<br><small class="sub" style="direction:ltr;">{{ $r->client_phone }}</small>
                                @if($r->client?->note)<br><small class="dim">{{ $r->client->note }}</small>@endif
                            </td>
                            <td><small class="time">{{ $r->starts_at->format('m/d H:i') }}</small></td>
                            <td><small class="time">{{ $r->ends_at->format('m/d H:i') }}</small></td>
                            <td style="font-weight:700;">{{ $h>0 ? $h.'س ' : '' }}{{ $m>0 ? $m.'د' : '' }}</td>
                            <td class="money">{{ number_format($r->totalCostEgp(),2) }} <small style="font-size:11px;">ج.م</small></td>
                            <td class="dim">{{ $r->note ?: '—' }}</td>
                            <td>
                                <form action="{{ route('workspace.rooms.reservations.cancel', $r->id) }}" method="POST" onsubmit="return confirm('إلغاء هذا الحجز؟');">
                                    @csrf
                                    <button type="submit" class="vd-btn vd-btn-danger">إلغاء</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
        </div>
    </div>

    {{-- ── Rooms manager ─────────────────────────────────────── --}}
    <div class="vd-card">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-door-open"></i></div>
                <div><h3>غرفك</h3><p>أضف غرفك وعدّل أسعارها أو احذفها نهائياً.</p></div>
            </div>
        </div>
        <div class="vd-body">
        <div class="manage-grid">
            {{-- List (primary) --}}
            <div class="manage-main">
                @if($rooms->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-door-closed"></i>
                        لا توجد غرف بعد. أضف غرفتك الأولى من النموذج المجاور.
                    </div>
                @else
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>الغرفة</th><th>ملاحظة الغرفة</th><th>سعر الساعة</th><th>الحالة</th><th>إجراء</th></tr></thead>
                        <tbody>
                            @foreach($rooms as $room)
                                <tr>
                                    <td style="font-weight:700;">{{ $room->name }}</td>
                                    <td class="dim">{{ $room->note ?: '—' }}</td>
                                    <td class="money">{{ number_format($room->hourlyPriceEgp(),2) }} <small style="font-size:11px;">ج.م</small></td>
                                    <td>
                                        @if($room->is_active)
                                            <span class="badge badge-on">فعّالة</span>
                                        @else
                                            <span class="badge badge-off">موقوفة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            @if($room->is_active)
                                                <form action="{{ route('workspace.rooms.deactivate', $room->id) }}" method="POST" onsubmit="return confirm('إيقاف هذه الغرفة؟ يمكن إعادة تفعيلها لاحقاً.');">
                                                    @csrf
                                                    <button type="submit" class="vd-btn">إيقاف</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('workspace.rooms.destroy', $room->id) }}" method="POST"
                                                  onsubmit="return confirm('حذف «{{ $room->name }}» نهائياً مع كل حجوزاتها؟ لا يمكن التراجع عن هذا الإجراء.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vd-btn vd-btn-danger"><i class="fa-solid fa-trash"></i> حذف</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>

            {{-- Add form (side) --}}
            <aside class="manage-aside">
                <form action="{{ route('workspace.rooms.store') }}" method="POST" class="manage-form-panel">
                    @csrf
                    <h4><i class="fa-solid fa-plus" style="color:var(--vd-accent);"></i> إضافة غرفة</h4>
                    <label style="font-size:13px;">اسم الغرفة</label>
                    <input type="text" name="name" class="form-control" placeholder="غرفة اجتماعات" required>
                    <label style="font-size:13px;">ملاحظة الغرفة</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="مثلاً: بها شاشة، ٦ كراسي، مناسبة للمذاكرة الهادئة">{{ old('note') }}</textarea>
                    <label style="font-size:13px;">سعر الساعة (ج.م)</label>
                    <input type="number" name="hourly_price_pounds" class="form-control" min="0" step="0.01" value="50" required>
                    <button type="submit" class="btn-primary" style="width:100%;"><i class="fa-solid fa-plus"></i> إضافة غرفة</button>
                </form>
            </aside>
        </div>
        </div>
    </div>

    {{-- ── Saved clients ─────────────────────────────────────── --}}
    <div class="vd-card">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-address-book"></i></div>
                <div><h3>عملاء الغرف المسجّلون</h3><p>العملاء المحفوظون لإعادة الحجز بسرعة.</p></div>
            </div>
        </div>
        <div class="vd-body">
        @if($clients->isEmpty())
            <div class="empty-state"><i class="fa-solid fa-user-slash"></i> لا يوجد عملاء بعد.</div>
        @else
            <div class="table-responsive">
            <table>
                <thead><tr>
                    <th>الاسم</th><th>الهاتف</th><th>ملاحظة</th><th>عدد الحجوزات</th><th>السجل</th>
                </tr></thead>
                <tbody>
                    @foreach($clients as $c)
                        <tr>
                            <td style="font-weight:700;">{{ $c->name }}</td>
                            <td class="time" style="direction:ltr; text-align:right;">{{ $c->phone }}</td>
                            <td class="dim">{{ $c->note ?: '—' }}</td>
                            <td>{{ $c->reservations_count }}</td>
                            <td><a href="{{ route('workspace.rooms.clients.show', $c->id) }}" class="vd-link">عرض السجل</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div style="margin-top:16px;">{{ $clients->links('vendor.pagination.upwork') }}</div>
        @endif
        </div>
    </div>
</div>
@endsection
