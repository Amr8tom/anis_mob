@extends('workspace.layouts.app')

@section('styles')
<style>
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 700; margin-bottom: 8px; font-size: 14px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid var(--upwork-input-border); border-radius: var(--radius-sm); font-family: inherit; }
    .btn-save { background: var(--upwork-green); color: white; padding: 12px 24px; border: none; border-radius: var(--radius-sm); cursor: pointer; font-weight: bold; }
    .form-error { color: var(--upwork-error); font-size: 13px; margin-top: 5px; }
</style>
@endsection

@section('content')
<div class="settings-container">
    <h1 class="page-title">إضافة جلسة دراسية جديدة</h1>
    
    <div class="card">
        <form action="{{ route('workspace.sessions.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>عنوان الجلسة</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>وصف الجلسة</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>اسم المدرب / المحاضر (اختياري)</label>
                <input type="text" name="instructor_name" class="form-control" value="{{ old('instructor_name') }}">
                @error('instructor_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>وقت البدء</label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    @error('start_time') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>وقت الانتهاء</label>
                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    @error('end_time') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>الحد الأقصى للحضور (اختياري)</label>
                    <input type="number" name="max_participants" class="form-control" min="1" value="{{ old('max_participants') }}">
                    @error('max_participants') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>السعر (بالقروش - مجاني = 0)</label>
                    <input type="number" name="price_cents" class="form-control" min="0" value="{{ old('price_cents', 0) }}" required>
                    @error('price_cents') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button type="submit" class="btn-save">حفظ الجلسة</button>
                <a href="{{ route('workspace.sessions.index') }}" style="padding: 12px 24px; color: var(--upwork-muted); text-decoration: none;">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
