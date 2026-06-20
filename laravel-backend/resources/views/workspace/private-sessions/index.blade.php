@extends('workspace.layouts.app')

@section('title', 'الجلسات الخاصة | بوابة مساحة العمل')

@section('content')
<div class="private-session-list-page">
    <div class="private-session-list-hero">
        <div>
            <h1 class="private-session-list-title">الجلسات الخاصة</h1>
            <p class="private-session-list-subtitle">جلسات داخل مساحة العمل فقط. لا تظهر في جلسات التطبيق العامة، والحضور يتم بقائمة أرقام و QR.</p>
        </div>
        <a href="{{ route('workspace.private-sessions.create') }}" class="btn-primary private-session-create-btn" style="text-decoration:none;"><i class="fa-solid fa-plus"></i> إنشاء جلسة خاصة</a>
    </div>

    @if($sessions->isEmpty())
        <div class="card empty-state private-session-dark-card">
            <i class="fa-solid fa-qrcode"></i>
            لا توجد جلسات خاصة بعد.
        </div>
    @else
        <div class="card private-session-dark-card">
            <div class="table-responsive">
                <table style="width:100%; border-collapse:collapse; text-align:right;">
                    <thead>
                        <tr>
                            <th style="padding:10px;">الجلسة</th>
                            <th style="padding:10px;">الموعد</th>
                            <th style="padding:10px;">السعر</th>
                            <th style="padding:10px;">الحضور</th>
                            <th style="padding:10px;">الإيراد</th>
                            <th style="padding:10px;">الحالة</th>
                            <th style="padding:10px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                            @php $summary = $session->summary(); @endphp
                            <tr>
                                <td style="padding:10px; font-weight:700;">{{ $session->title }}</td>
                                <td style="padding:10px;"><small>{{ $session->starts_at->format('Y/m/d H:i') }}</small></td>
                                <td style="padding:10px;">{{ number_format($session->priceEgp(), 2) }} ج.م</td>
                                <td style="padding:10px;">{{ $summary['attended'] }} / {{ $summary['invited'] }}</td>
                                <td class="private-session-money" style="padding:10px;">{{ number_format($summary['actual_revenue_cents'] / 100, 2) }} ج.م</td>
                                <td style="padding:10px;"><span class="private-session-status private-session-status-{{ $session->status }}">{{ ['active'=>'فعالة','finished'=>'منتهية','cancelled'=>'ملغية'][$session->status] ?? $session->status }}</span></td>
                                <td style="padding:10px;"><a href="{{ route('workspace.private-sessions.show', $session) }}" class="private-session-manage-link">إدارة</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;">{{ $sessions->links('vendor.pagination.upwork') }}</div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .private-session-list-page {
        --ps-surface: #16211b;
        --ps-surface-2: #1d2c24;
        --ps-input: #0f1813;
        --ps-border: #2c3d33;
        --ps-text: #eef3f0;
        --ps-muted: #9db0a4;
        --ps-dim: #6c7e73;
        --ps-accent: #2bd968;
    }

    .private-session-list-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        padding: 24px;
        border-radius: var(--radius-md);
        border: 1px solid var(--ps-border);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .13), transparent 28%),
            linear-gradient(135deg, #16211b 0%, #1d2c24 100%);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .private-session-list-title {
        margin: 0 0 8px;
        color: var(--ps-text);
        font-size: 34px;
        font-weight: 900;
        line-height: 1.2;
    }

    .private-session-list-subtitle {
        margin: 0;
        color: var(--ps-muted);
        font-size: 15px;
        font-weight: 700;
    }

    .private-session-create-btn {
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding-inline: 22px;
    }

    .private-session-list-page .private-session-dark-card {
        background: var(--ps-surface) !important;
        color: var(--ps-text) !important;
        border: 1px solid var(--ps-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .private-session-list-page table {
        color: var(--ps-text);
    }

    .private-session-list-page thead tr {
        background: var(--ps-surface-2) !important;
        border-bottom: 2px solid var(--ps-border) !important;
    }

    .private-session-list-page tbody tr {
        border-bottom: 1px solid var(--ps-border) !important;
    }

    .private-session-list-page tbody tr:hover {
        background: #21322a;
    }

    .private-session-list-page th {
        color: var(--ps-muted);
        font-weight: 800;
    }

    .private-session-list-page td,
    .private-session-list-page small {
        color: var(--ps-text);
    }

    .private-session-money {
        color: var(--ps-accent) !important;
        font-weight: 900;
    }

    .private-session-manage-link {
        color: #7ef7a4;
        text-decoration: none;
        font-weight: 800;
    }

    .private-session-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid var(--ps-border);
        color: var(--ps-muted);
        background: var(--ps-input);
    }

    .private-session-status-active {
        color: #7ef7a4;
        background: rgba(43, 217, 104, .12);
        border-color: rgba(43, 217, 104, .34);
    }

    .private-session-status-finished {
        color: #bfdbfe;
        background: rgba(59, 130, 246, .14);
        border-color: rgba(96, 165, 250, .28);
    }

    .private-session-status-cancelled {
        color: #fecaca;
        background: rgba(185, 28, 28, .16);
        border-color: rgba(248, 113, 113, .32);
    }

    .private-session-list-page .empty-state {
        color: var(--ps-muted);
    }
</style>
@endsection
