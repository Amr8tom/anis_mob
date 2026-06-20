@extends('workspace.layouts.app')

@section('title', 'QR الجلسة الخاصة')

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.private-sessions.show', $session) }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">→ رجوع للجلسة</a>
    <h1 class="page-title" style="margin:8px 0 6px;">QR الجلسة الخاصة</h1>
    <p class="page-subtitle">يعرض هذا الرمز رابط تسجيل حضور الجلسة. يجب أن يكون المستخدم مسجلاً في التطبيق ومضافاً مسبقاً لهذه الجلسة بنفس رقم الهاتف.</p>

    <div class="card" style="text-align:center;">
        <h2 style="margin-top:0;">{{ $session->title }}</h2>
        <img alt="QR" style="width:260px; height:260px; border:1px solid var(--upwork-border); border-radius:16px; padding:10px; background:#fff;"
             src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data={{ urlencode($checkInUrl) }}">
        <p style="font-size:12px; color:var(--upwork-muted); word-break:break-all; direction:ltr;">{{ $checkInUrl }}</p>
        <p style="font-size:13px; color:var(--upwork-muted);">للتطبيق: أرسل <code style="direction:ltr;">qr_token={{ $session->qr_token }}</code> إلى API تسجيل حضور الجلسة الخاصة.</p>
        <button onclick="window.print()" class="btn-primary" type="button"><i class="fa-solid fa-print"></i> طباعة</button>
    </div>
</div>
@endsection
