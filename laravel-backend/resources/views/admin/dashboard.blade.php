@extends('admin.layout.app')

@section('title', 'لوحة القيادة | بوابة الإدارة')
@section('header', 'نظرة عامة على المنصة')

@section('content')
<div class="grid-4">
    <!-- Stat Card 1 -->
    <div class="card" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600;">إجمالي مساحات العمل النشطة</h3>
            <div style="color: var(--upwork-green); background-color: var(--upwork-green-soft); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ $stats['active_workspaces'] }}</p>
    </div>

    <!-- Stat Card 2 -->
    <div class="card" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600;">الاشتراكات النشطة</h3>
            <div style="color: #108a00; background-color: #f4fdf4; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ $stats['active_subscriptions'] }}</p>
    </div>

    <!-- Stat Card 3 -->
    <div class="card" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600;">فترات تم دفعها هذا الشهر</h3>
            <div style="color: #d97706; background-color: #fef3c7; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ number_format($stats['paid_periods_this_month']) }}</p>
    </div>

    <!-- Stat Card 4 -->
    <div class="card" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600;">زيارات اليوم</h3>
            <div style="color: #6366f1; background-color: #e0e7ff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ $stats['todays_visits'] }}</p>
        <p style="color: var(--upwork-muted); font-size: 13px; margin-top: 8px;">{{ $stats['active_visits'] }} نشط حالياً</p>
    </div>
</div>

<div class="grid-4" style="margin-top: 20px;">
    <div class="card" style="padding: 24px;">
        <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600; margin-bottom: 16px;">إجمالي المستخدمين</h3>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ number_format($stats['total_users'] ?? 0) }}</p>
    </div>
    <div class="card" style="padding: 24px;">
        <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600; margin-bottom: 16px;">إجمالي ساعات الزيارة هذا الشهر</h3>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ $stats['hours_this_month'] ?? 0 }}</p>
    </div>
    <div class="card" style="padding: 24px;">
        <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600; margin-bottom: 16px;">زوّار مسجّلون يدويًا (Walk-in)</h3>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ number_format($stats['walk_in_users'] ?? 0) }}</p>
    </div>
    <div class="card" style="padding: 24px;">
        <h3 style="color: var(--upwork-muted); font-size: 14px; font-weight: 600; margin-bottom: 16px;">اشتراكات فضية / ذهبية</h3>
        <p style="font-size: 32px; font-weight: 800; color: var(--upwork-slate);">{{ $stats['silver_subscriptions'] ?? 0 }} / {{ $stats['gold_subscriptions'] ?? 0 }}</p>
    </div>
</div>

<!-- Placeholder for future sections like Recent Activity or Workspace list -->
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-slate-800">Recent Activity</h2>
        <button class="text-sm text-indigo-600 font-medium hover:text-indigo-700">View All</button>
    </div>
    <div class="p-6 flex flex-col items-center justify-center text-slate-400 py-12">
        <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <p>Activity logs will appear here</p>
    </div>
</div>
@endsection
