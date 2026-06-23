@extends('workspace.layouts.app')

@section('title', __('portal.rooms.client.meta_title'))

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.rooms.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">{{ __('portal.rooms.client.back') }}</a>
    <h1 class="page-title" style="margin:8px 0 6px;">{{ $client->name }}</h1>
    <p class="page-subtitle" style="direction:ltr; text-align:right;">{{ $client->phone }}</p>
    @if($client->note)
        <div class="card" style="margin-bottom:16px; border-right:4px solid var(--upwork-green);">
            <strong>{{ __('portal.rooms.client.note_label') }}</strong>
            <span style="color:var(--upwork-muted);">{{ $client->note }}</span>
        </div>
    @endif

    <div class="card">
        <h3 style="margin:0 0 16px;">{{ __('portal.rooms.client.history_title') }}</h3>
        @if($reservations->isEmpty())
            <p style="text-align:center; color:var(--upwork-muted); padding:16px;">{{ __('portal.rooms.client.empty') }}</p>
        @else
            <div class="table-responsive">
            <table style="width:100%; border-collapse:collapse; text-align:right;">
                <thead><tr style="border-bottom:2px solid var(--upwork-border);">
                    <th style="padding:10px;">{{ __('portal.rooms.client.th_room') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_from') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_to') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_duration') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_cost') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_note') }}</th><th style="padding:10px;">{{ __('portal.rooms.client.th_status') }}</th>
                </tr></thead>
                <tbody>
                    @foreach($reservations as $r)
                        @php $mins=$r->durationMinutes(); $h=intdiv($mins,60); $m=$mins%60; @endphp
                        <tr style="border-bottom:1px solid var(--upwork-border);">
                            <td style="padding:10px; font-weight:700;">
                                {{ $r->room->name ?? '—' }}
                                @if($r->room?->note)
                                    <br><small style="color:var(--upwork-muted); font-weight:500;">{{ $r->room->note }}</small>
                                @endif
                            </td>
                            <td style="padding:10px;"><small>{{ $r->starts_at->format('Y/m/d H:i') }}</small></td>
                            <td style="padding:10px;"><small>{{ $r->ends_at->format('Y/m/d H:i') }}</small></td>
                            <td style="padding:10px;">{{ $h>0 ? $h.__('portal.rooms.unit_hour').' ' : '' }}{{ $m>0 ? $m.__('portal.rooms.unit_minute') : '' }}</td>
                            <td style="padding:10px; font-weight:700; color:var(--upwork-green-dark);">{{ number_format($r->totalCostEgp(),2) }} {{ __('portal.egp') }}</td>
                            <td style="padding:10px; color:var(--upwork-muted);">{{ $r->note ?: '—' }}</td>
                            <td style="padding:10px;">
                                @if($r->status->value === 'RESERVED')
                                    <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">{{ __('portal.rooms.client.status_reserved') }}</span>
                                @else
                                    <span style="background:#fdecec; color:#b91c1c; padding:3px 9px; border-radius:20px; font-size:12px; font-weight:700;">{{ __('portal.rooms.client.status_cancelled') }}</span>
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
