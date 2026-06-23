@extends('workspace.layouts.app')

@section('title', __('portal.rooms.meta_title'))

@section('styles')
@include('workspace.partials.dark-theme')
@endsection

@section('content')
<div class="visits-dark">
    <h1 class="page-title" style="margin:0 0 6px;">{{ __('portal.rooms.title') }}</h1>
    <p class="page-subtitle">{{ __('portal.rooms.subtitle') }}</p>

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
                    <h3>{{ __('portal.rooms.new.title') }}</h3>
                    <p>{{ __('portal.rooms.new.subtitle') }}</p>
                </div>
            </div>
        </div>
        <div class="vd-body">
        @if($activeRooms->isEmpty())
            <p style="color:var(--vd-text-muted); margin:0;">{{ __('portal.rooms.new.no_rooms') }}</p>
        @else
            <form action="{{ route('workspace.rooms.reservations.store') }}" method="POST">
                @csrf
                <div class="grid-3" style="gap:14px;">
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.room') }}</label>
                        <select name="room_id" class="form-control" required>
                            @foreach($activeRooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} — {{ number_format($room->hourlyPriceEgp(), 2) }} {{ __('portal.rooms.per_hour') }}{{ $room->note ? ' — '.$room->note : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.client_name') }}</label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" list="room-client-names" placeholder="{{ __('portal.rooms.new.client_name_placeholder') }}">
                    </div>
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.client_phone') }}</label>
                        <input type="text" name="client_phone" class="form-control" style="direction:ltr; text-align:right;" placeholder="01xxxxxxxxx" value="{{ old('client_phone') }}" list="room-client-phones" required>
                        <small style="color:var(--vd-text-dim); font-size:11px;">{{ __('portal.rooms.new.client_phone_hint') }}</small>
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
                        <label style="font-size:13px;">{{ __('portal.rooms.new.date') }}</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
                    </div>
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.start_time') }}</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time', '16:00') }}" required>
                    </div>
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.duration') }}</label>
                        <select name="duration_minutes" class="form-control" required>
                            @foreach([30,60,90,120,180,240] as $min)
                                <option value="{{ $min }}" {{ (int)old('duration_minutes',60)===$min ? 'selected' : '' }}>{{ __('portal.rooms.durations.'.$min) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Recurrence --}}
                <div class="vd-softbox" style="margin-top:14px; padding:14px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:700; color:var(--vd-text);">
                        <input type="checkbox" name="recurring" value="1" id="recurring-toggle" onchange="document.getElementById('recurrence-options').style.display=this.checked?'block':'none'">
                        {{ __('portal.rooms.new.recurring') }}
                    </label>
                    <div id="recurrence-options" style="display:none; margin-top:12px;">
                        <label style="font-size:13px; display:block; margin-bottom:6px;">{{ __('portal.rooms.new.recurrence_days') }}</label>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            @foreach([0,1,2,3,4,5,6] as $d)
                                <label class="vd-chip" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; cursor:pointer; font-size:13px;">
                                    <input type="checkbox" name="weekdays[]" value="{{ $d }}"> {{ __('portal.rooms.weekdays.'.$d) }}
                                </label>
                            @endforeach
                        </div>
                        <label style="font-size:13px; display:block; margin-top:10px;">{{ __('portal.rooms.new.until') }}</label>
                        <input type="date" name="until" class="form-control" style="max-width:240px;">
                        <small style="color:var(--vd-text-dim); font-size:11px; display:block; margin-top:4px;">{{ __('portal.rooms.new.until_hint') }}</small>
                    </div>
                </div>

                <div class="grid-2" style="gap:14px; margin-top:12px;">
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.client_note') }}</label>
                        <textarea name="client_note" class="form-control" rows="2" placeholder="{{ __('portal.rooms.new.client_note_placeholder') }}">{{ old('client_note') }}</textarea>
                    </div>
                    <div>
                        <label style="font-size:13px;">{{ __('portal.rooms.new.note') }}</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="{{ __('portal.rooms.new.note_placeholder') }}">{{ old('note') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="margin-top:14px;"><i class="fa-solid fa-check"></i> {{ __('portal.rooms.new.confirm') }}</button>
            </form>
        @endif
        </div>
    </div>

    {{-- ── Upcoming reservations ─────────────────────────────── --}}
    <div class="vd-card">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div><h3>{{ __('portal.rooms.upcoming.title') }}</h3><p>{{ __('portal.rooms.upcoming.subtitle') }}</p></div>
            </div>
        </div>
        <div class="vd-body">
        @if($upcoming->isEmpty())
            <div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i> {{ __('portal.rooms.upcoming.empty') }}</div>
        @else
            <div class="table-responsive">
            <table>
                <thead><tr>
                    <th>{{ __('portal.rooms.upcoming.th_room') }}</th><th>{{ __('portal.rooms.upcoming.th_client') }}</th><th>{{ __('portal.rooms.upcoming.th_from') }}</th><th>{{ __('portal.rooms.upcoming.th_to') }}</th><th>{{ __('portal.rooms.upcoming.th_duration') }}</th><th>{{ __('portal.rooms.upcoming.th_cost') }}</th><th>{{ __('portal.rooms.upcoming.th_notes') }}</th><th>{{ __('portal.rooms.upcoming.th_action') }}</th>
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
                            <td style="font-weight:700;">{{ $h>0 ? $h.__('portal.rooms.unit_hour').' ' : '' }}{{ $m>0 ? $m.__('portal.rooms.unit_minute') : '' }}</td>
                            <td class="money">{{ number_format($r->totalCostEgp(),2) }} <small style="font-size:11px;">{{ __('portal.egp') }}</small></td>
                            <td class="dim">{{ $r->note ?: '—' }}</td>
                            <td>
                                <form action="{{ route('workspace.rooms.reservations.cancel', $r->id) }}" method="POST" onsubmit="return confirm('{{ __('portal.rooms.upcoming.cancel_confirm') }}');">
                                    @csrf
                                    <button type="submit" class="vd-btn vd-btn-danger">{{ __('portal.rooms.upcoming.cancel') }}</button>
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
                <div><h3>{{ __('portal.rooms.manager.title') }}</h3><p>{{ __('portal.rooms.manager.subtitle') }}</p></div>
            </div>
        </div>
        <div class="vd-body">
        <div class="manage-grid">
            {{-- List (primary) --}}
            <div class="manage-main">
                @if($rooms->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-door-closed"></i>
                        {{ __('portal.rooms.manager.empty') }}
                    </div>
                @else
                    <div class="table-responsive">
                    <table>
                        <thead><tr><th>{{ __('portal.rooms.manager.th_room') }}</th><th>{{ __('portal.rooms.manager.th_note') }}</th><th>{{ __('portal.rooms.manager.th_hourly') }}</th><th>{{ __('portal.rooms.manager.th_status') }}</th><th>{{ __('portal.rooms.manager.th_action') }}</th></tr></thead>
                        <tbody>
                            @foreach($rooms as $room)
                                <tr>
                                    <td style="font-weight:700;">{{ $room->name }}</td>
                                    <td class="dim">{{ $room->note ?: '—' }}</td>
                                    <td class="money">{{ number_format($room->hourlyPriceEgp(),2) }} <small style="font-size:11px;">{{ __('portal.egp') }}</small></td>
                                    <td>
                                        @if($room->is_active)
                                            <span class="badge badge-on">{{ __('portal.rooms.manager.status_active') }}</span>
                                        @else
                                            <span class="badge badge-off">{{ __('portal.rooms.manager.status_inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            @if($room->is_active)
                                                <form action="{{ route('workspace.rooms.deactivate', $room->id) }}" method="POST" onsubmit="return confirm('{{ __('portal.rooms.manager.deactivate_confirm') }}');">
                                                    @csrf
                                                    <button type="submit" class="vd-btn">{{ __('portal.rooms.manager.deactivate') }}</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('workspace.rooms.destroy', $room->id) }}" method="POST"
                                                  onsubmit="return confirm('{{ __('portal.rooms.manager.delete_confirm', ['name' => $room->name]) }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vd-btn vd-btn-danger"><i class="fa-solid fa-trash"></i> {{ __('portal.rooms.manager.delete') }}</button>
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
                    <h4><i class="fa-solid fa-plus" style="color:var(--vd-accent);"></i> {{ __('portal.rooms.manager.add_title') }}</h4>
                    <label style="font-size:13px;">{{ __('portal.rooms.manager.name') }}</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ __('portal.rooms.manager.name_placeholder') }}" required>
                    <label style="font-size:13px;">{{ __('portal.rooms.manager.note') }}</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="{{ __('portal.rooms.manager.note_placeholder') }}">{{ old('note') }}</textarea>
                    <label style="font-size:13px;">{{ __('portal.rooms.manager.hourly_price') }}</label>
                    <input type="number" name="hourly_price_pounds" class="form-control" min="0" step="0.01" value="50" required>
                    <button type="submit" class="btn-primary" style="width:100%;"><i class="fa-solid fa-plus"></i> {{ __('portal.rooms.manager.add_title') }}</button>
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
                <div><h3>{{ __('portal.rooms.clients.title') }}</h3><p>{{ __('portal.rooms.clients.subtitle') }}</p></div>
            </div>
        </div>
        <div class="vd-body">
        @if($clients->isEmpty())
            <div class="empty-state"><i class="fa-solid fa-user-slash"></i> {{ __('portal.rooms.clients.empty') }}</div>
        @else
            <div class="table-responsive">
            <table>
                <thead><tr>
                    <th>{{ __('portal.rooms.clients.th_name') }}</th><th>{{ __('portal.rooms.clients.th_phone') }}</th><th>{{ __('portal.rooms.clients.th_note') }}</th><th>{{ __('portal.rooms.clients.th_count') }}</th><th>{{ __('portal.rooms.clients.th_history') }}</th>
                </tr></thead>
                <tbody>
                    @foreach($clients as $c)
                        <tr>
                            <td style="font-weight:700;">{{ $c->name }}</td>
                            <td class="time" style="direction:ltr; text-align:right;">{{ $c->phone }}</td>
                            <td class="dim">{{ $c->note ?: '—' }}</td>
                            <td>{{ $c->reservations_count }}</td>
                            <td><a href="{{ route('workspace.rooms.clients.show', $c->id) }}" class="vd-link">{{ __('portal.rooms.clients.view_history') }}</a></td>
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
