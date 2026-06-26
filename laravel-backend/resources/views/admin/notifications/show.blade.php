@extends('admin.layout.app')

@section('title', 'تفاصيل الإشعار | بوابة الإدارة')
@section('header', 'تفاصيل حملة الإشعار')

@section('styles')
<style>
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }
    .summary-card {
        border: 1px solid var(--upwork-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: var(--upwork-bg);
    }
    .summary-value {
        font-size: 28px;
        font-weight: 900;
        color: var(--upwork-green);
        line-height: 1.1;
    }
    .summary-label {
        color: var(--upwork-muted);
        font-size: 13px;
        font-weight: 700;
        margin-top: 6px;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: 1px solid var(--upwork-border);
        background: var(--upwork-bg);
    }
    .badge-sent { color: var(--upwork-green); background: var(--upwork-success-bg); }
    .badge-failed { color: var(--upwork-error); background: var(--upwork-error-bg); }
    .badge-skipped { color: var(--upwork-muted); }
    .badge-pending { color: #2563eb; }
    .detail-muted { color: var(--upwork-muted); font-size: 12px; }
    .token-preview {
        max-width: 160px;
        direction: ltr;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .actions-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 18px;
    }
    @media (max-width: 900px) {
        .summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
@endsection

@section('content')
<div class="actions-bar">
    <a href="{{ route('admin.notifications.index') }}" class="btn-primary" style="background: var(--upwork-slate);">رجوع للسجل</a>
    @if($campaign->failed_count > 0)
        <form action="{{ route('admin.notifications.retry-failed', $campaign) }}" method="POST" onsubmit="return confirm('إعادة إرسال كل المستلمين الفاشلين القابلين للإعادة؟');">
            @csrf
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-rotate"></i> إعادة إرسال الفاشل
            </button>
        </form>
    @endif
</div>

<div class="card">
    <h3 class="card-title"><i class="fa-solid fa-paper-plane" style="color: var(--upwork-green);"></i> {{ $campaign->title }}</h3>
    <p style="color: var(--upwork-muted); margin-bottom: 18px;">{{ $campaign->body }}</p>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->targeted_count) }}</div>
            <div class="summary-label">إجمالي المستهدفين</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->sent_count) }}</div>
            <div class="summary-label">تم الإرسال</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->failed_count) }}</div>
            <div class="summary-label">فشل</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->skipped_count) }}</div>
            <div class="summary-label">تم التجاوز</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->opened_count) }}</div>
            <div class="summary-label">فتح</div>
        </div>
        <div class="summary-card">
            <div class="summary-value">{{ number_format($campaign->clicked_count) }}</div>
            <div class="summary-label">ضغط</div>
        </div>
    </div>

    <div class="grid-2">
        <div>
            <strong>الحالة:</strong> {{ $campaign->status }}
            <div class="detail-muted">النوع: {{ $campaign->target_type }}</div>
            <div class="detail-muted">التصنيف: {{ $campaign->notification_category }}</div>
        </div>
        <div>
            <strong>وقت الإنشاء:</strong> {{ $campaign->created_at?->format('Y-m-d H:i') }}
            <div class="detail-muted">موعد الإرسال: {{ $campaign->scheduled_at?->format('Y-m-d H:i') ?? 'فوري' }}</div>
            <div class="detail-muted">المساحة: {{ $campaign->workspace?->name ?? 'كل التطبيق' }}</div>
        </div>
    </div>

    @if($campaign->recipients_pruned_at)
        <div class="alert alert-success" style="margin-top: 18px;">
            <i class="fa-solid fa-box-archive"></i>
            <div>تم تنظيف تفاصيل بعض المستلمين لهذه الحملة بعد مدة الاحتفاظ. ملخص الحملة محفوظ كما هو.</div>
        </div>
    @endif
</div>

<div class="card">
    <h3 class="card-title"><i class="fa-solid fa-users" style="color: var(--upwork-green);"></i> المستلمون</h3>
    @if($recipients->isEmpty())
        <div style="color: var(--upwork-muted);">لا توجد تفاصيل مستلمين محفوظة لهذه الحملة.</div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الهاتف</th>
                        <th>الجهاز</th>
                        <th>الحالة</th>
                        <th>سبب الفشل / التجاوز</th>
                        <th>الإرسال والتفاعل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipients as $recipient)
                        <tr>
                            <td>
                                <div style="font-weight: 800;">{{ $recipient->user?->full_name ?? 'مستخدم غير معروف' }}</div>
                                <div class="detail-muted">{{ $recipient->user_id }}</div>
                            </td>
                            <td dir="ltr" style="text-align: start;">{{ $recipient->user?->phone_number ?? '-' }}</td>
                            <td>
                                <div>{{ $recipient->deviceToken?->platform ?? '-' }}</div>
                                <div class="detail-muted token-preview">{{ $recipient->deviceToken?->token ?? '-' }}</div>
                                @if($recipient->deviceToken && ! $recipient->deviceToken->is_active)
                                    <div class="detail-muted">غير نشط</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $recipient->status }}">{{ $recipient->status }}</span>
                            </td>
                            <td>
                                <div>{{ $recipient->error_code ?? '-' }}</div>
                                @if($recipient->error_message)
                                    <div class="detail-muted">{{ $recipient->error_message }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="detail-muted">أُرسل: {{ $recipient->sent_at?->format('Y-m-d H:i') ?? '-' }}</div>
                                <div class="detail-muted">فتح: {{ $recipient->opened_at?->format('Y-m-d H:i') ?? '-' }} ({{ $recipient->open_count }})</div>
                                <div class="detail-muted">ضغط: {{ $recipient->clicked_at?->format('Y-m-d H:i') ?? '-' }} ({{ $recipient->click_count }})</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 18px;">
            {{ $recipients->links() }}
        </div>
    @endif
</div>
@endsection
