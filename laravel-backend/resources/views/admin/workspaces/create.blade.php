@extends('admin.layout.app')

@section('title', 'إضافة مساحة عمل | بوابة الإدارة')
@section('header', 'إضافة مساحة عمل')

@section('content')
<div class="card">
    <form action="{{ route('admin.workspaces.store') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div><label>اسم مساحة العمل</label><input class="form-input" name="name" value="{{ old('name') }}" required></div>
            <div><label>رقم التواصل</label><input class="form-input" name="admin_phone" value="{{ old('admin_phone') }}"></div>
            <div><label>رقم هاتف المالك</label><input class="form-input" name="owner_phone" value="{{ old('owner_phone') }}" required></div>
        </div>
        <label>العنوان</label>
        <input class="form-input" name="address" value="{{ old('address') }}" required>
        <label>الوصف</label>
        <textarea class="form-input" name="description" rows="3">{{ old('description') }}</textarea>
        <div class="grid-2">
            <div><label>خط العرض</label><input class="form-input" type="number" step="any" name="latitude" value="{{ old('latitude') }}"></div>
            <div><label>خط الطول</label><input class="form-input" type="number" step="any" name="longitude" value="{{ old('longitude') }}"></div>
            <div><label>السعة</label><input class="form-input" type="number" min="1" name="capacity" value="{{ old('capacity') }}"></div>
            <div></div>
            <div><label>وقت الفتح</label><input class="form-input" type="time" name="open_time" value="{{ old('open_time') }}"></div>
            <div><label>وقت الإغلاق</label><input class="form-input" type="time" name="close_time" value="{{ old('close_time') }}"></div>
        </div>
        <button class="btn-primary" type="submit">إنشاء مساحة العمل</button>
    </form>
</div>
@endsection
