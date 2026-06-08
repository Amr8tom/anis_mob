@extends('workspace.layouts.app')

@section('content')
<div class="settings-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title" style="margin: 0;">الجلسات الدراسية وورش العمل</h1>
        <a href="{{ route('workspace.sessions.create') }}" class="btn-action btn-action-primary" style="padding: 10px 20px; border-radius: var(--radius-sm); text-decoration: none; color: white; background: var(--upwork-green);">
            <i class="fa-solid fa-plus"></i> إضافة جلسة جديدة
        </a>
    </div>

    @if(session('success'))
        <div style="background: var(--upwork-green-soft); color: var(--upwork-green-dark); padding: 15px; border-radius: var(--radius-sm); margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        @if($sessions->count() > 0)
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--upwork-border);">
                        <th style="padding: 12px;">عنوان الجلسة</th>
                        <th style="padding: 12px;">المدرب / المحاضر</th>
                        <th style="padding: 12px;">التوقيت</th>
                        <th style="padding: 12px;">السعر</th>
                        <th style="padding: 12px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr style="border-bottom: 1px solid var(--upwork-border);">
                            <td style="padding: 12px;">{{ $session->title }}</td>
                            <td style="padding: 12px;">{{ $session->instructor_name ?? 'غير محدد' }}</td>
                            <td style="padding: 12px;">
                                {{ \Carbon\Carbon::parse($session->start_time)->format('Y-m-d h:i A') }}<br>
                                <small>إلى: {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</small>
                            </td>
                            <td style="padding: 12px;">{{ number_format($session->price_cents / 100, 2) }} ج.م</td>
                            <td style="padding: 12px; display: flex; gap: 10px;">
                                <a href="{{ route('workspace.sessions.edit', $session) }}" style="color: var(--upwork-blue); text-decoration: none;">تعديل</a>
                                <form action="{{ route('workspace.sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجلسة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: var(--upwork-error); background: none; border: none; cursor: pointer; padding: 0;">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                {{ $sessions->links() }}
            </div>
        @else
            <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">لا توجد جلسات حالياً. أضف جلستك الأولى الآن!</p>
        @endif
    </div>
</div>
@endsection
