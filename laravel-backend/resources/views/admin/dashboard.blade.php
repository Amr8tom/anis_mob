@extends('admin.layout.app')

@section('title', 'لوحة القيادة | بوابة الإدارة')
@section('header', 'نظرة عامة على المنصة')

@section('content')
<div class="grid-4">
    <div class="card admin-stat-card">
        <div class="admin-stat-head">
            <h3 class="admin-stat-label">إجمالي مساحات العمل النشطة</h3>
            <div class="admin-stat-icon">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
        <p class="admin-stat-value">{{ number_format($stats['active_workspaces']) }}</p>
    </div>

    <div class="card admin-stat-card">
        <div class="admin-stat-head">
            <h3 class="admin-stat-label">الاشتراكات النشطة</h3>
            <div class="admin-stat-icon">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>
        <p class="admin-stat-value">{{ number_format($stats['active_subscriptions']) }}</p>
    </div>

    <div class="card admin-stat-card">
        <div class="admin-stat-head">
            <h3 class="admin-stat-label">فترات تم دفعها هذا الشهر</h3>
            <div class="admin-stat-icon" style="color:#b76e00; background:#fff7e6;">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>
        <p class="admin-stat-value">{{ number_format($stats['paid_periods_this_month']) }}</p>
    </div>

    <div class="card admin-stat-card">
        <div class="admin-stat-head">
            <h3 class="admin-stat-label">زيارات اليوم</h3>
            <div class="admin-stat-icon" style="color:#4f46e5; background:#eef2ff;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="admin-stat-value">{{ number_format($stats['todays_visits']) }}</p>
        <p style="color: var(--upwork-muted); font-size: 13px; margin-top: 8px;">{{ $stats['active_visits'] }} نشط حالياً</p>
    </div>
</div>

<div class="grid-4" style="margin-top: 20px;">
    <div class="card admin-stat-card">
        <h3 class="admin-stat-label" style="margin-bottom: 16px;">إجمالي المستخدمين</h3>
        <p class="admin-stat-value">{{ number_format($stats['total_users'] ?? 0) }}</p>
    </div>
    <div class="card admin-stat-card">
        <h3 class="admin-stat-label" style="margin-bottom: 16px;">إجمالي ساعات الزيارة هذا الشهر</h3>
        <p class="admin-stat-value">{{ number_format((float) ($stats['hours_this_month'] ?? 0), 1) }}</p>
    </div>
    <div class="card admin-stat-card">
        <h3 class="admin-stat-label" style="margin-bottom: 16px;">زوّار مسجّلون يدويًا (Walk-in)</h3>
        <p class="admin-stat-value">{{ number_format($stats['walk_in_users'] ?? 0) }}</p>
    </div>
    <div class="card admin-stat-card">
        <h3 class="admin-stat-label" style="margin-bottom: 16px;">اشتراكات فضية / ذهبية</h3>
        <p class="admin-stat-value">{{ number_format($stats['silver_subscriptions'] ?? 0) }} / {{ number_format($stats['gold_subscriptions'] ?? 0) }}</p>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:18px;">
        <h2 class="card-title" style="margin:0; padding:0; border:0;">
            <i class="fa-solid fa-clock-rotate-left" style="color:var(--upwork-green);"></i>
            آخر النشاطات
        </h2>
        <a href="{{ route('admin.workspaces.index') }}" class="btn-primary" style="background:transparent; color:var(--upwork-green-dark); border:1px solid var(--upwork-border);">
            عرض مساحات العمل
        </a>
    </div>
    <div class="empty-state">
        <i class="fa-solid fa-list-check"></i>
        <strong style="color:var(--upwork-slate); font-size:17px;">لا توجد نشاطات حديثة للعرض الآن</strong>
        <p style="margin-top:6px;">هنا سيظهر ملخص سريع لطلبات التسجيل، المدفوعات، وحركات الإدارة المهمة.</p>
    </div>
</div>
@endsection
