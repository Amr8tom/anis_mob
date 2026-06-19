@extends('workspace.layouts.app')

@section('title', 'قبول دعوة مساحة العمل')

@section('content')
<div class="card" style="max-width:560px; margin:40px auto;">
    <h2>قبول دعوة ملكية مساحة العمل</h2>
    <p style="margin:12px 0 20px;">سيتم ربط الحساب رقم {{ $invitation->phone_number }} بمساحة العمل.</p>
    <form action="{{ route('workspace.invitations.accept', $token) }}" method="POST">
        @csrf
        <label>الاسم الكامل</label>
        <input class="form-input" name="full_name" required>
        <label>رقم واتساب</label>
        <input class="form-input" name="whatsapp_number" value="{{ $invitation->phone_number }}" required>
        <label>كلمة المرور</label>
        <input class="form-input" type="password" name="password" required>
        <label>تأكيد كلمة المرور</label>
        <input class="form-input" type="password" name="password_confirmation" required>
        <button class="btn-primary" type="submit">قبول الدعوة</button>
    </form>
</div>
@endsection
