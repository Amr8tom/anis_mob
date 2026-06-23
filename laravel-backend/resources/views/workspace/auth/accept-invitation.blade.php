@extends('workspace.layouts.app')

@section('title', __('portal.auth.invitation.meta_title'))

@section('content')
<div class="card" style="max-width:560px; margin:40px auto;">
    <h2>{{ __('portal.auth.invitation.title') }}</h2>
    <p style="margin:12px 0 20px;">{{ __('portal.auth.invitation.intro', ['phone' => $invitation->phone_number]) }}</p>
    <form action="{{ route('workspace.invitations.accept', $token) }}" method="POST">
        @csrf
        <label>{{ __('portal.auth.invitation.full_name') }}</label>
        <input class="form-input" name="full_name" required>
        <label>{{ __('portal.auth.invitation.whatsapp') }}</label>
        <input class="form-input" name="whatsapp_number" value="{{ $invitation->phone_number }}" required>
        <label>{{ __('portal.auth.invitation.password') }}</label>
        <input class="form-input" type="password" name="password" required>
        <label>{{ __('portal.auth.invitation.password_confirm') }}</label>
        <input class="form-input" type="password" name="password_confirmation" required>
        <button class="btn-primary" type="submit">{{ __('portal.auth.invitation.accept') }}</button>
    </form>
</div>
@endsection
