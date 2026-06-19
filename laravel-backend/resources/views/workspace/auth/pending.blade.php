@extends('workspace.layouts.app')

@section('content')
    <div class="auth-card" style="max-width: 560px; margin: 60px auto;">
        <div class="card" style="text-align: center; padding: 48px 32px;">
            <div style="width: 84px; height: 84px; margin: 0 auto 24px; border-radius: 50%;
                        background-color: var(--upwork-success-bg); display: flex; align-items: center;
                        justify-content: center;">
                <i class="fa-solid fa-hourglass-half" style="font-size: 36px; color: var(--upwork-green);"></i>
            </div>

            <h1 class="page-title" style="margin-bottom: 12px;">تم استلام طلبك بنجاح</h1>

            <p class="page-subtitle" style="margin-bottom: 8px; line-height: 1.9;">
                مساحة عملك الآن قيد المراجعة من قبل فريق الإدارة. سنقوم بمراجعة بياناتك
                والصور التي أرفقتها، وسيتم تفعيل حسابك بمجرد الموافقة.
            </p>

            <p class="page-subtitle" style="color: var(--upwork-muted); margin-bottom: 32px;">
                بعد الموافقة ستتمكن من تسجيل الدخول وإدارة مساحتك مباشرةً.
            </p>

            <a href="{{ route('workspace.login') }}" class="btn-submit"
               style="display: inline-flex; align-items: center; gap: 10px; background-color: var(--upwork-green);
                      color: #fff; padding: 14px 28px; border-radius: var(--radius-sm); font-weight: 700;
                      text-decoration: none;">
                <i class="fa-solid fa-arrow-left-to-bracket"></i>
                <span>الذهاب لتسجيل الدخول</span>
            </a>
        </div>
    </div>
@endsection
