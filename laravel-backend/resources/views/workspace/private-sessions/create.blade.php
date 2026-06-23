@extends('workspace.layouts.app')

@section('title', __('portal.private_sessions.meta_title'))

@section('content')
<div class="settings-container">
    <a href="{{ route('workspace.private-sessions.index') }}" style="color:var(--upwork-blue); text-decoration:none; font-size:13px;">{{ __('portal.private_sessions.create.back') }}</a>
    <h1 class="page-title" style="margin:8px 0 6px;">{{ __('portal.private_sessions.create.title') }}</h1>
    <p class="page-subtitle">{{ __('portal.private_sessions.create.subtitle') }}</p>

    <div class="card">
        <form action="{{ route('workspace.private-sessions.store') }}" method="POST">
            @csrf
            <div style="margin-bottom:16px;">
                <label>{{ __('portal.private_sessions.create.session_title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div style="margin-bottom:16px;">
                <label>{{ __('portal.private_sessions.create.description') }}</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="grid-2" style="gap:14px; margin-bottom:16px;">
                <div>
                    <label>{{ __('portal.private_sessions.create.host') }}</label>
                    <input type="text" name="host_name" class="form-control" value="{{ old('host_name') }}">
                </div>
                <div>
                    <label>{{ __('portal.private_sessions.create.price') }}</label>
                    <input type="number" name="price_pounds" class="form-control" min="0" step="0.01" value="{{ old('price_pounds', 0) }}" required>
                </div>
            </div>
            <div class="card" style="background:var(--upwork-bg); margin-bottom:16px;">
                <h3 style="font-size:18px; margin-bottom:14px;">{{ __('portal.private_sessions.create.edu_data') }}</h3>
                <div class="grid-3" style="gap:14px;">
                    <div>
                        <label>{{ __('portal.private_sessions.create.teacher') }}</label>
                        <select name="center_teacher_id" class="form-control">
                            <option value="">{{ __('portal.private_sessions.none') }}</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('center_teacher_id') === $teacher->id)>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>{{ __('portal.private_sessions.create.subject') }}</label>
                        <select name="center_subject_id" class="form-control">
                            <option value="">{{ __('portal.private_sessions.none') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(old('center_subject_id') === $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>{{ __('portal.private_sessions.create.grade') }}</label>
                        <select name="center_grade_level_id" class="form-control">
                            <option value="">{{ __('portal.private_sessions.none') }}</option>
                            @foreach($gradeLevels as $gradeLevel)
                                <option value="{{ $gradeLevel->id }}" @selected(old('center_grade_level_id') === $gradeLevel->id)>{{ $gradeLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p style="color:var(--upwork-muted); margin-top:2px;">{{ __('portal.private_sessions.create.edu_hint') }}</p>
            </div>
            <div class="grid-2" style="gap:14px; margin-bottom:16px;">
                <div>
                    <label>{{ __('portal.private_sessions.create.payout_type') }}</label>
                    <select name="instructor_payout_type" class="form-control">
                        <option value="none" @selected(old('instructor_payout_type', 'none') === 'none')>{{ __('portal.private_sessions.create.payout_none') }}</option>
                        <option value="percentage" @selected(old('instructor_payout_type') === 'percentage')>{{ __('portal.private_sessions.create.payout_percentage') }}</option>
                        <option value="per_attendee_fixed" @selected(old('instructor_payout_type') === 'per_attendee_fixed')>{{ __('portal.private_sessions.create.payout_per_attendee') }}</option>
                        <option value="session_fixed" @selected(old('instructor_payout_type') === 'session_fixed')>{{ __('portal.private_sessions.create.payout_session') }}</option>
                    </select>
                </div>
                <div>
                    <label>{{ __('portal.private_sessions.create.payout_value') }}</label>
                    <input type="number" name="instructor_payout_value" class="form-control" min="0" step="0.01" value="{{ old('instructor_payout_value', 0) }}">
                    <small style="color:var(--upwork-muted);">{{ __('portal.private_sessions.create.payout_hint') }}</small>
                </div>
            </div>
            <div class="grid-3" style="gap:14px; margin-bottom:16px;">
                <div>
                    <label>{{ __('portal.private_sessions.create.starts_at') }}</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div>
                    <label>{{ __('portal.private_sessions.create.ends_at') }}</label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                </div>
                <div>
                    <label>{{ __('portal.private_sessions.create.capacity') }}</label>
                    <input type="number" name="capacity" class="form-control" min="1" value="{{ old('capacity') }}">
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <label>{{ __('portal.private_sessions.create.notes') }}</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> {{ __('portal.private_sessions.create.submit') }}</button>
        </form>
    </div>
</div>
@endsection
