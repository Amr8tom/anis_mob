@extends('workspace.layouts.app')

@section('title', 'إنشاء جلسة خاصة | بوابة مساحة العمل')

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.private-sessions.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">→ رجوع للجلسات الخاصة</a>
    <h1 class="page-title" style="margin:8px 0 6px;">إنشاء جلسة خاصة</h1>
    <p class="page-subtitle">هذه الجلسة ستكون خاصة بمساحتك فقط، ولن تظهر في قائمة الجلسات العامة داخل التطبيق.</p>

    <div class="card">
        <form action="{{ route('workspace.private-sessions.store') }}" method="POST">
            @csrf
            <div style="margin-bottom:16px;">
                <label>عنوان الجلسة</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div style="margin-bottom:16px;">
                <label>الوصف</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="grid-2" style="gap:14px; margin-bottom:16px;">
                <div>
                    <label>اسم المحاضر / المسؤول</label>
                    <input type="text" name="host_name" class="form-control" value="{{ old('host_name') }}">
                </div>
                <div>
                    <label>سعر حضور الفرد (ج.م)</label>
                    <input type="number" name="price_pounds" class="form-control" min="0" step="0.01" value="{{ old('price_pounds', 0) }}" required>
                </div>
            </div>
            <div class="grid-3" style="gap:14px; margin-bottom:16px;">
                <div>
                    <label>بداية الجلسة</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div>
                    <label>نهاية الجلسة</label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                </div>
                <div>
                    <label>السعة</label>
                    <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity') }}">
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <label>ملاحظات داخلية</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> إنشاء الجلسة</button>
        </form>
    </div>
</div>
@endsection
