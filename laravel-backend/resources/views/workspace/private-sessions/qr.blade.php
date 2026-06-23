@extends('workspace.layouts.app')

@section('title', __('portal.private_sessions.qr.meta_title'))

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.private-sessions.show', $session) }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">{{ __('portal.private_sessions.qr.back') }}</a>
    <h1 class="page-title" style="margin:8px 0 6px;">{{ __('portal.private_sessions.qr.title') }}</h1>
    <p class="page-subtitle">{{ __('portal.private_sessions.qr.subtitle') }}</p>

    <div class="card" style="text-align:center;">
        <h2 style="margin-top:0;">{{ $session->title }}</h2>
        <img alt="QR" style="width:260px; height:260px; border:1px solid var(--upwork-border); border-radius:16px; padding:10px; background:#fff;"
             src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data={{ urlencode($checkInUrl) }}">
        <p style="font-size:12px; color:var(--upwork-muted); word-break:break-all; direction:ltr;">{{ $checkInUrl }}</p>
        <p style="font-size:13px; color:var(--upwork-muted);">{{ __('portal.private_sessions.qr.api_note_before') }} <code style="direction:ltr;">qr_token={{ $session->qr_token }}</code> {{ __('portal.private_sessions.qr.api_note_after') }}</p>
        <button onclick="window.print()" class="btn-primary" type="button"><i class="fa-solid fa-print"></i> {{ __('portal.private_sessions.qr.print') }}</button>
    </div>
</div>
@endsection
