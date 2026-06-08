@extends('workspace.layouts.app')

@section('styles')
    <style>
        .auth-card {
            max-width: 480px;
            margin: 60px auto;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-family: var(--font-family);
            font-size: 15px;
            border: 1px solid var(--upwork-input-border);
            border-radius: var(--radius-sm);
            background-color: #ffffff;
            color: var(--upwork-slate);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--upwork-green);
            box-shadow: 0 0 0 3px rgba(20, 168, 0, 0.1);
        }

        .form-error {
            color: var(--upwork-error);
            font-size: 13px;
            font-weight: 600;
            margin-top: 6px;
        }

        .btn-submit {
            background-color: var(--upwork-green);
            color: #ffffff;
            font-family: var(--font-family);
            font-size: 16px;
            font-weight: 700;
            padding: 14px 28px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background-color: var(--upwork-green-dark);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--upwork-muted);
            font-size: 15px;
        }

        .auth-footer a {
            color: var(--upwork-green);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <div class="auth-card">
        <h1 class="page-title text-center" style="text-align: center; margin-bottom: 8px;">بوابة الشركاء</h1>
        <p class="page-subtitle text-center" style="text-align: center; margin-bottom: 30px;">
            سجل الدخول لإدارة تفاصيل مساحتك ومعرض الصور الخاص بها.
        </p>

        <div class="card">
            <form action="{{ route('workspace.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="phone_number">رقم الهاتف</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="01xxxxxxxxx" value="{{ old('phone_number') }}" required autocomplete="username">
                    @error('phone_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit" style="margin-top: 10px;">
                    <i class="fa-solid fa-arrow-left-to-bracket"></i>
                    <span>تسجيل الدخول</span>
                </button>
            </form>
        </div>

        <div class="auth-footer">
            ليس لديك حساب شريك؟ <a href="{{ route('workspace.register') }}">سجل مساحتك الآن</a>
        </div>
    </div>
@endsection
