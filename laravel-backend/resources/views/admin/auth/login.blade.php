@extends('admin.layout.app')

@section('title', 'تسجيل الدخول | بوابة الإدارة')

@section('styles')
<style>
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 70px);
        background-color: var(--upwork-bg);
    }
    .login-card {
        width: 100%;
        max-width: 440px;
        background-color: var(--upwork-card-bg);
        border: 1px solid var(--upwork-border);
        border-radius: var(--radius-md);
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .login-header {
        text-align: center;
        margin-bottom: 32px;
    }
    .login-header h2 {
        font-size: 24px;
        font-weight: 800;
        color: var(--upwork-slate);
        margin-bottom: 8px;
    }
    .login-header p {
        color: var(--upwork-muted);
        font-size: 15px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--upwork-slate);
        margin-bottom: 8px;
    }
    .form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--upwork-input-border);
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 15px;
        transition: var(--transition);
    }
    .form-group input:focus {
        outline: none;
        border-color: var(--upwork-green);
        box-shadow: 0 0 0 2px rgba(20, 168, 0, 0.1);
    }
    .btn-login {
        width: 100%;
        padding: 14px;
        background-color: var(--upwork-green);
        color: white;
        border: none;
        border-radius: 99px;
        font-weight: 700;
        font-size: 16px;
        font-family: inherit;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 12px;
    }
    .btn-login:hover {
        background-color: var(--upwork-green-dark);
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div style="background-color: var(--upwork-slate); color: white; width: 48px; height: 48px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; margin: 0 auto 16px;">أ</div>
            <h2>بوابة الإدارة</h2>
            <p>سجل دخولك للوصول إلى لوحة التحكم</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 24px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" name="phone_number" required placeholder="مثال: 01012345678" style="direction: ltr; text-align: right;">
            </div>
            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required placeholder="••••••••" style="direction: ltr; text-align: right;">
            </div>
            <button type="submit" class="btn-login">
                تسجيل الدخول
            </button>
        </form>
    </div>
</div>
@endsection
