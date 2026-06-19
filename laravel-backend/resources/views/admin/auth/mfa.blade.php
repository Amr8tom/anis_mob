@extends('admin.layout.app')

@section('title', 'التحقق بخطوتين | بوابة الإدارة')

@section('content')
<div class="card" style="max-width:440px; margin:80px auto;">
    <h2 class="card-title">التحقق بخطوتين</h2>
    <p style="color:var(--upwork-muted); margin-bottom:20px;">أدخل الرمز المكون من 6 أرقام من تطبيق المصادقة.</p>
    <form action="{{ route('admin.mfa.verify') }}" method="POST">
        @csrf
        <input class="form-input" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required>
        <button class="btn-primary" type="submit">تحقق</button>
    </form>
</div>
@endsection
