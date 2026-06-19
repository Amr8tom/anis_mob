@extends('workspace.layouts.app')

@section('title', 'سجل العميل | حجوزات الغرف')

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.rooms.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">→ رجوع لحجوزات الغرف</a>
    <h1 class="page-title" style="margin:8px 0 6px;">{{ $client->name }}</h1>
    <p class="page-subtitle" style="direction:ltr; text-align:right;">{{ $client->phone }}</p>

    <div class="card">
        <h3 style="margin:0 0 16px;">سجل الحجوزات</h3>
        @if($reservations->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:16px;">لا توجد حجوزات لهذا العميل.</p>
        @else
            <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:10px;">الغرفة</th><th style="padding:10px;">من</th><th style="padding:10px;">إلى</th><th style="padding:10px;">المدة</th><th style="padding:10px;">التكلفة</th><th style="padding:10px;">الحالة</th>
                </tr></thead>
                <tbody>
                    @foreach($reservations as $r)
                        @php $mins=$r->durationMinutes(); $h=intdiv($mins,60); $m=$mins%60; @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px; font-weight:700;">{{ $r->room->name ?? '—' }}</td>
                            <td style="padding:10px;"><small>{{ $r->starts_at->format('Y/m/d H:i') }}</small></td>
                            <td style="padding:10px;"><small>{{ $r->ends_at->format('Y/m/d H:i') }}</small></td>
                            <td style="padding:10px;">{{ $h>0 ? $h.'س ' : '' }}{{ $m>0 ? $m.'د' : '' }}</td>
                            <td style="padding:10px; font-weight:700; color:var(--upwork-green-dark);">{{ number_format($r->totalCostEgp(),2) }} ج.م</td>
                            <td style="padding:10px;">
                                @if($r->status->value === 'RESERVED')
                                    <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">محجوز</span>
                                @else
                                    <span style="background:#fdecec; color:#b91c1c; padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">ملغي</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div style="margin-top:16px;">{{ $reservations->links('vendor.pagination.upwork') }}</div>
        @endif
    </div>
</div>
@endsection
