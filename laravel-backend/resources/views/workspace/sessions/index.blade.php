@extends('workspace.layouts.app')

@section('title', __('portal.sessions.meta_title'))

@section('styles')
@include('workspace.partials.dark-theme')
@endsection

@section('content')
<div class="visits-dark">

    {{-- Hero header --}}
    <div class="vd-card vd-card--accent" style="margin-bottom:22px;">
        <div class="vd-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div class="vd-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div>
                    <h3 style="font-size:20px;">{{ __('portal.sessions.title') }}</h3>
                    <p>{{ __('portal.sessions.subtitle') }}</p>
                </div>
            </div>
            <a href="{{ route('workspace.sessions.create') }}" class="btn-primary" style="text-decoration:none;">
                <i class="fa-solid fa-plus"></i> {{ __('portal.sessions.add') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="vd-card vd-card--pad" style="margin-bottom:16px; border-right:4px solid var(--vd-accent); color:var(--vd-accent);">{{ session('success') }}</div>
    @endif

    {{-- Sessions table --}}
    <div class="vd-card">
        <div class="vd-body">
        @if($sessions->count() > 0)
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('portal.sessions.th_title') }}</th>
                        <th>{{ __('portal.sessions.th_instructor') }}</th>
                        <th>{{ __('portal.sessions.th_time') }}</th>
                        <th>{{ __('portal.sessions.th_price') }}</th>
                        <th>{{ __('portal.sessions.th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        @php $priceEgp = $session->price_cents / 100; @endphp
                        <tr>
                            <td style="font-weight:700;">{{ $session->title }}</td>
                            <td class="{{ $session->instructor_name ? '' : 'dim' }}">{{ $session->instructor_name ?? __('portal.sessions.instructor_unset') }}</td>
                            <td>
                                <span class="time">{{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d h:i A') }}</span><br>
                                <small class="dim">{{ __('portal.sessions.to_prefix') }} {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</small>
                            </td>
                            <td>
                                @if($priceEgp > 0)
                                    <span class="money">{{ number_format($priceEgp, 2) }} <small style="font-size:11px;">{{ __('portal.egp') }}</small></span>
                                @else
                                    <span class="badge badge-free">{{ __('portal.billing.FREE') }}</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                                    <a href="{{ route('workspace.sessions.edit', $session) }}" class="vd-btn"><i class="fa-solid fa-pen"></i> {{ __('portal.sessions.edit') }}</a>
                                    <form action="{{ route('workspace.sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('{{ __('portal.sessions.delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vd-btn vd-btn-danger"><i class="fa-solid fa-trash"></i> {{ __('portal.sessions.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="margin-top:20px;">
                {{ $sessions->links('vendor.pagination.upwork') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-chalkboard-user"></i>
                {{ __('portal.sessions.empty') }}
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
