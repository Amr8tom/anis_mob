@extends('workspace.layouts.app')

@section('title', 'حجوزات الغرف | بوابة مساحة العمل')

@section('content')
<div>
    <h1 class="page-title" style="margin:0 0 6px;">حجوزات الغرف</h1>
    <p class="page-subtitle">سجّل حجوزات غرفك بسرعة للزائر العادي أو مستخدم التطبيق. اكتب رقم الهاتف فقط للعميل المحفوظ، أو أضف الاسم مرة واحدة للزائر الجديد.</p>

    @if(session('success'))
        <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green); color:var(--upwork-green-dark);">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-error); color:var(--upwork-error);">{{ $errors->first() }}</div>
    @endif

    {{-- ── New reservation ───────────────────────────────────── --}}
    <div class="card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 16px;"><i class="fa-solid fa-calendar-plus"></i> حجز غرفة</h3>
        @if($activeRooms->isEmpty())
            <p style="color:var(--upwork-muted);">أضف غرفة أولاً من القسم بالأسفل لتتمكن من الحجز.</p>
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
                        <small style="color:var(--upwork-muted); font-size:11px;">يدعم زائر walk-in أو مستخدم تطبيق. لو الرقم محفوظ، سيتم ملء الاسم تلقائياً في الخلفية.</small>
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
                <div style="margin-top:14px; padding:14px; background:var(--upwork-bg); border:1px solid var(--upwork-border); border-radius:var(--radius-sm);">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:700;">
                        <input type="checkbox" name="recurring" value="1" id="recurring-toggle" onchange="document.getElementById('recurrence-options').style.display=this.checked?'block':'none'">
                        تكرار أسبوعي
                    </label>
                    <div id="recurrence-options" style="display:none; margin-top:12px;">
                        <label style="font-size:13px; display:block; margin-bottom:6px;">أيام التكرار</label>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            @foreach([0=>'الأحد',1=>'الإثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس',5=>'الجمعة',6=>'السبت'] as $d=>$label)
                                <label style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; border:1px solid var(--upwork-border); border-radius:20px; cursor:pointer; font-size:13px;">
                                    <input type="checkbox" name="weekdays[]" value="{{ $d }}"> {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        <label style="font-size:13px; display:block; margin-top:10px;">حتى تاريخ (اختياري، بحد أقصى ١٢ أسبوعاً)</label>
                        <input type="date" name="until" class="form-control" style="max-width:240px;">
                        <small style="color:var(--upwork-muted); font-size:11px; display:block; margin-top:4px;">المواعيد المتعارضة مع حجوزات قائمة سيتم تخطّيها وإبلاغك بها.</small>
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

    {{-- ── Upcoming reservations ─────────────────────────────── --}}
    <div class="card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 16px;">الحجوزات القادمة</h3>
        @if($upcoming->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:16px;">لا توجد حجوزات قادمة.</p>
        @else
            <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:10px;">الغرفة</th><th style="padding:10px;">العميل</th><th style="padding:10px;">من</th><th style="padding:10px;">إلى</th><th style="padding:10px;">المدة</th><th style="padding:10px;">التكلفة</th><th style="padding:10px;">ملاحظات</th><th style="padding:10px;">إجراء</th>
                </tr></thead>
                <tbody>
                    @foreach($upcoming as $r)
                        @php $mins=$r->durationMinutes(); $h=intdiv($mins,60); $m=$mins%60; @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px; font-weight:700;">
                                {{ $r->room->name ?? '—' }}
                                @if($r->room?->note)
                                    <br><small style="color:var(--upwork-muted); font-weight:500;">{{ $r->room->note }}</small>
                                @endif
                            </td>
                            <td style="padding:10px;">
                                {{ $r->client_name }}<br><small style="color:var(--upwork-muted); direction:ltr;">{{ $r->client_phone }}</small>
                                @if($r->client?->note)
                                    <br><small style="color:var(--upwork-muted);">{{ $r->client->note }}</small>
                                @endif
                            </td>
                            <td style="padding:10px;"><small>{{ $r->starts_at->format('m/d H:i') }}</small></td>
                            <td style="padding:10px;"><small>{{ $r->ends_at->format('m/d H:i') }}</small></td>
                            <td style="padding:10px;">{{ $h>0 ? $h.'س ' : '' }}{{ $m>0 ? $m.'د' : '' }}</td>
                            <td style="padding:10px; font-weight:700; color:var(--upwork-green-dark);">{{ number_format($r->totalCostEgp(),2) }} ج.م</td>
                            <td style="padding:10px; color:var(--upwork-muted);">{{ $r->note ?: '—' }}</td>
                            <td style="padding:10px;">
                                <form action="{{ route('workspace.rooms.reservations.cancel', $r->id) }}" method="POST" onsubmit="return confirm('إلغاء هذا الحجز؟');">
                                    @csrf
                                    <button type="submit" style="padding:6px 12px; border:1px solid var(--upwork-border); background:#fff; border-radius:var(--radius-sm); cursor:pointer; font-weight:700; color:var(--upwork-error);">إلغاء</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

    {{-- ── Rooms manager ─────────────────────────────────────── --}}
    <div class="card" style="margin-bottom:22px;">
        <h3 style="margin:0 0 18px;"><i class="fa-solid fa-door-open"></i> غرفك</h3>
        <div class="manage-grid">
            {{-- List (primary) --}}
            <div class="manage-main">
                @if($rooms->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-door-closed"></i>
                        لا توجد غرف بعد. أضف غرفتك الأولى من النموذج المجاور.
                    </div>
                @else
                    <table>
                        <thead><tr><th>الغرفة</th><th>ملاحظة الغرفة</th><th>سعر الساعة</th><th>الحالة</th><th>إجراء</th></tr></thead>
                        <tbody>
                            @foreach($rooms as $room)
                                <tr>
                                    <td style="font-weight:700;">{{ $room->name }}</td>
                                    <td style="color:var(--upwork-muted);">{{ $room->note ?: '—' }}</td>
                                    <td>{{ number_format($room->hourlyPriceEgp(),2) }} ج.م</td>
                                    <td>
                                        @if($room->is_active)
                                            <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">فعّالة</span>
                                        @else
                                            <span style="background:#eee; color:#777; padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">موقوفة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($room->is_active)
                                            <form action="{{ route('workspace.rooms.deactivate', $room->id) }}" method="POST" onsubmit="return confirm('إيقاف هذه الغرفة؟');">
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
                <form action="{{ route('workspace.rooms.store') }}" method="POST" class="manage-form-panel">
                    @csrf
                    <h4><i class="fa-solid fa-plus" style="color:var(--upwork-green);"></i> إضافة غرفة</h4>
                    <label style="font-size:13px;">اسم الغرفة</label>
                    <input type="text" name="name" class="form-control" placeholder="غرفة اجتماعات" required>
                    <label style="font-size:13px;">ملاحظة الغرفة</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="مثلاً: بها شاشة، ٦ كراسي، مناسبة للمذاكرة الهادئة">{{ old('note') }}</textarea>
                    <label style="font-size:13px;">سعر الساعة (ج.م)</label>
                    <input type="number" name="hourly_price_pounds" class="form-control" min="0" step="0.01" value="50" required>
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-plus"></i> إضافة غرفة</button>
                </form>
            </aside>
        </div>
    </div>

    {{-- ── Saved clients ─────────────────────────────────────── --}}
    <div class="card">
        <h3 style="margin:0 0 16px;">عملاء الغرف المسجّلون</h3>
        @if($clients->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:16px;">لا يوجد عملاء بعد.</p>
        @else
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:10px;">الاسم</th><th style="padding:10px;">الهاتف</th><th style="padding:10px;">ملاحظة</th><th style="padding:10px;">عدد الحجوزات</th><th style="padding:10px;">السجل</th>
                </tr></thead>
                <tbody>
                    @foreach($clients as $c)
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px; font-weight:700;">{{ $c->name }}</td>
                            <td style="padding:10px; direction:ltr; text-align:right;">{{ $c->phone }}</td>
                            <td style="padding:10px; color:var(--upwork-muted);">{{ $c->note ?: '—' }}</td>
                            <td style="padding:10px;">{{ $c->reservations_count }}</td>
                            <td style="padding:10px;"><a href="{{ route('workspace.rooms.clients.show', $c->id) }}" style="color:var(--upwork-blue); text-decoration:none;">عرض السجل</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">{{ $clients->links('vendor.pagination.upwork') }}</div>
        @endif
    </div>
</div>
@endsection
