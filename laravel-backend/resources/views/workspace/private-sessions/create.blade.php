@extends('workspace.layouts.app')

@section('title', __('portal.private_sessions.meta_title'))

@section('content')
<div class="settings-container private-session-create-page">
    <a href="{{ route('workspace.private-sessions.index') }}" class="private-session-back-link">{{ __('portal.private_sessions.create.back') }}</a>
    <div class="private-session-create-hero">
        <div>
            <h1 class="page-title private-session-create-title">{{ __('portal.private_sessions.create.title') }}</h1>
            <p class="page-subtitle private-session-create-subtitle">{{ __('portal.private_sessions.create.subtitle') }}</p>
        </div>
    </div>

    <div class="card private-session-create-card">
        @php
            $educationEnabled = old('use_education_data', (old('center_teacher_id') || old('center_subject_id') || old('center_grade_level_id')) ? '1' : '0') === '1';
        @endphp
        <form action="{{ route('workspace.private-sessions.store') }}" method="POST">
            @csrf

            <section class="private-session-section">
                <div class="private-session-section-head">
                    <div>
                        <span class="private-session-step">1</span>
                        <h2>{{ __('portal.private_sessions.create.basic_section') }}</h2>
                    </div>
                    <p>{{ __('portal.private_sessions.create.basic_hint') }}</p>
                </div>

                <div class="private-session-field">
                    <label>{{ __('portal.private_sessions.create.session_title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="{{ __('portal.private_sessions.create.title_placeholder') }}">
                </div>

                <div class="private-session-field">
                    <label>{{ __('portal.private_sessions.create.description') }}</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="{{ __('portal.private_sessions.create.description_placeholder') }}">{{ old('description') }}</textarea>
                </div>

                <div class="grid-2 private-session-form-grid">
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.host') }}</label>
                        <input type="text" name="host_name" id="hostNameInput" class="form-control" value="{{ old('host_name') }}" placeholder="{{ __('portal.private_sessions.create.host_placeholder') }}">
                    </div>
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.price') }}</label>
                        <div class="private-session-input-affix">
                            <input type="number" name="price_pounds" class="form-control" min="0" step="0.01" inputmode="decimal" value="{{ old('price_pounds', 0) }}" required>
                            <span>{{ __('portal.egp') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="private-session-section">
                <label class="private-session-toggle-card" for="useEducationData">
                    <input type="checkbox" name="use_education_data" id="useEducationData" value="1" @checked($educationEnabled)>
                    <span class="private-session-toggle-switch" aria-hidden="true"></span>
                    <span>
                        <strong>{{ __('portal.private_sessions.create.use_registered_education') }}</strong>
                        <small>{{ __('portal.private_sessions.create.use_registered_education_hint') }}</small>
                    </span>
                </label>

                <div class="private-session-form-panel" id="educationPanel">
                    <div class="private-session-panel-head">
                        <div>
                            <span class="private-session-step">2</span>
                            <h2>{{ __('portal.private_sessions.create.edu_data') }}</h2>
                        </div>
                        <a href="{{ route('workspace.education.index') }}">{{ __('portal.private_sessions.create.manage_education') }}</a>
                    </div>

                    <div class="grid-3 private-session-form-grid">
                        <div class="private-session-field">
                            <label>{{ __('portal.private_sessions.create.teacher') }}</label>
                            <select name="center_teacher_id" id="centerTeacherSelect" class="form-control education-control">
                                <option value="">{{ __('portal.private_sessions.none') }}</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}" @selected((string) old('center_teacher_id') === (string) $teacher->id)>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="private-session-field">
                            <label>{{ __('portal.private_sessions.create.subject') }}</label>
                            <select name="center_subject_id" class="form-control education-control">
                                <option value="">{{ __('portal.private_sessions.none') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected((string) old('center_subject_id') === (string) $subject->id)>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="private-session-field">
                            <label>{{ __('portal.private_sessions.create.grade') }}</label>
                            <select name="center_grade_level_id" class="form-control education-control">
                                <option value="">{{ __('portal.private_sessions.none') }}</option>
                                @foreach($gradeLevels as $gradeLevel)
                                    <option value="{{ $gradeLevel->id }}" @selected((string) old('center_grade_level_id') === (string) $gradeLevel->id)>{{ $gradeLevel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <p class="private-session-help">{{ __('portal.private_sessions.create.edu_hint') }}</p>
                </div>
            </section>

            <section class="private-session-section private-session-section-compact">
                <div class="private-session-section-head">
                    <div>
                        <span class="private-session-step">3</span>
                        <h2>{{ __('portal.private_sessions.create.finance_section') }}</h2>
                    </div>
                    <p>{{ __('portal.private_sessions.create.finance_hint') }}</p>
                </div>

                <div class="grid-2 private-session-form-grid">
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.payout_type') }}</label>
                        <select name="instructor_payout_type" class="form-control">
                            <option value="none" @selected(old('instructor_payout_type', 'none') === 'none')>{{ __('portal.private_sessions.create.payout_none') }}</option>
                            <option value="percentage" @selected(old('instructor_payout_type') === 'percentage')>{{ __('portal.private_sessions.create.payout_percentage') }}</option>
                            <option value="per_attendee_fixed" @selected(old('instructor_payout_type') === 'per_attendee_fixed')>{{ __('portal.private_sessions.create.payout_per_attendee') }}</option>
                            <option value="session_fixed" @selected(old('instructor_payout_type') === 'session_fixed')>{{ __('portal.private_sessions.create.payout_session') }}</option>
                        </select>
                    </div>
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.payout_value') }}</label>
                        <input type="number" name="instructor_payout_value" class="form-control" min="0" step="0.01" inputmode="decimal" value="{{ old('instructor_payout_value', 0) }}">
                    </div>
                </div>
                <small class="private-session-help private-session-help-tight">{{ __('portal.private_sessions.create.payout_hint') }}</small>
            </section>

            <section class="private-session-section private-session-section-compact">
                <div class="private-session-section-head">
                    <div>
                        <span class="private-session-step">4</span>
                        <h2>{{ __('portal.private_sessions.create.schedule_section') }}</h2>
                    </div>
                    <p>{{ __('portal.private_sessions.create.schedule_hint') }}</p>
                </div>

                <div class="grid-3 private-session-form-grid">
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.starts_at') }}</label>
                        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.ends_at') }}</label>
                        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                    </div>
                    <div class="private-session-field">
                        <label>{{ __('portal.private_sessions.create.capacity') }}</label>
                        <input type="number" name="capacity" class="form-control" min="1" inputmode="numeric" value="{{ old('capacity') }}" placeholder="{{ __('portal.private_sessions.create.capacity_placeholder') }}">
                    </div>
                </div>

                <div class="private-session-field">
                    <label>{{ __('portal.private_sessions.create.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('portal.private_sessions.create.notes_placeholder') }}">{{ old('notes') }}</textarea>
                </div>
            </section>

            <div class="private-session-submit-row">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> {{ __('portal.private_sessions.create.submit') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .private-session-create-page {
        --ps-surface: #16211b;
        --ps-surface-2: #1d2c24;
        --ps-input: #0f1813;
        --ps-border: #2c3d33;
        --ps-text: #eef3f0;
        --ps-muted: #9db0a4;
        --ps-dim: #6c7e73;
        --ps-accent: #2bd968;
        --ps-hero-title: #eef3f0;
        --ps-hero-muted: #9db0a4;
    }

    .private-session-back-link {
        color: var(--ps-link, var(--upwork-blue));
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
    }

    .private-session-create-hero,
    .private-session-create-card,
    .private-session-form-panel {
        border: 1px solid var(--ps-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .12) !important;
    }

    .private-session-create-hero {
        margin: 8px 0 18px;
        padding: 22px;
        border-radius: var(--radius-md);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .12), transparent 28%),
            linear-gradient(135deg, var(--ps-surface) 0%, var(--ps-surface-2) 100%);
    }

    .private-session-create-title {
        margin: 0 0 6px !important;
        color: var(--ps-hero-title) !important;
    }

    .private-session-create-subtitle {
        color: var(--ps-hero-muted) !important;
        font-weight: 700;
    }

    .private-session-create-card {
        background: var(--ps-surface) !important;
        color: var(--ps-text) !important;
        padding: clamp(18px, 3vw, 30px) !important;
    }

    .private-session-section {
        border: 1px solid var(--ps-border);
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, .025);
        padding: 18px;
        margin-bottom: 18px;
    }

    .private-session-section-compact {
        padding-bottom: 8px;
    }

    .private-session-section-head,
    .private-session-panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .private-session-section-head > div,
    .private-session-panel-head > div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .private-session-section-head h2,
    .private-session-panel-head h2 {
        margin: 0;
        color: var(--ps-text);
        font-size: 18px;
        font-weight: 900;
    }

    .private-session-section-head p {
        margin: 0;
        color: var(--ps-muted);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.6;
        max-width: 520px;
    }

    .private-session-step {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-grid;
        place-items: center;
        background: rgba(43, 217, 104, .14);
        color: var(--ps-accent);
        border: 1px solid rgba(43, 217, 104, .32);
        font-size: 13px;
        font-weight: 900;
    }

    .private-session-form-panel {
        background: var(--ps-surface-2);
        border-radius: var(--radius-md);
        padding: 18px;
        margin-top: 14px;
        margin-bottom: 0;
    }

    .private-session-form-panel h3 {
        color: var(--ps-text);
        font-weight: 900;
    }

    .private-session-form-grid {
        gap: 16px !important;
        margin-bottom: 18px !important;
    }

    .private-session-field {
        margin-bottom: 18px;
    }

    .private-session-field label {
        display: block;
        color: var(--ps-text);
        font-size: 14px;
        font-weight: 900;
        margin-bottom: 8px;
    }

    .private-session-create-page .form-control {
        min-height: 48px;
        background: var(--ps-input) !important;
        border: 1px solid var(--ps-border) !important;
        color: var(--ps-text) !important;
        border-radius: var(--radius-sm);
        font-weight: 700;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .03);
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .private-session-create-page textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .private-session-create-page .form-control:focus {
        border-color: var(--ps-accent) !important;
        box-shadow: 0 0 0 3px rgba(43, 217, 104, .16);
    }

    .private-session-create-page .form-control::placeholder {
        color: var(--ps-dim);
    }

    .private-session-help {
        display: block;
        color: var(--ps-muted) !important;
        margin-top: 8px;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.7;
    }

    .private-session-help-tight {
        margin-top: -6px;
    }

    .private-session-input-affix {
        position: relative;
    }

    .private-session-input-affix .form-control {
        padding-inline-end: 64px;
    }

    .private-session-input-affix span {
        position: absolute;
        inset-inline-end: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ps-muted);
        font-weight: 900;
        pointer-events: none;
    }

    .private-session-toggle-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        border-radius: var(--radius-md);
        border: 1px solid var(--ps-border);
        background: linear-gradient(135deg, rgba(43, 217, 104, .08), rgba(255, 255, 255, .025));
        cursor: pointer;
        margin-bottom: 0;
    }

    .private-session-toggle-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .private-session-toggle-card strong,
    .private-session-toggle-card small {
        display: block;
    }

    .private-session-toggle-card strong {
        color: var(--ps-text);
        font-size: 15px;
        font-weight: 900;
        margin-bottom: 3px;
    }

    .private-session-toggle-card small {
        color: var(--ps-muted);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;
    }

    .private-session-toggle-switch {
        width: 48px;
        height: 28px;
        border-radius: 999px;
        background: var(--ps-input);
        border: 1px solid var(--ps-border);
        position: relative;
        flex: 0 0 auto;
        transition: background .18s ease, border-color .18s ease;
    }

    .private-session-toggle-switch::after {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        top: 3px;
        inset-inline-start: 4px;
        background: var(--ps-muted);
        transition: transform .18s ease, background .18s ease;
    }

    .private-session-toggle-card input:checked + .private-session-toggle-switch {
        background: rgba(43, 217, 104, .18);
        border-color: rgba(43, 217, 104, .52);
    }

    .private-session-toggle-card input:checked + .private-session-toggle-switch::after {
        background: var(--ps-accent);
        transform: translateX(-19px);
    }

    html[dir="ltr"] .private-session-toggle-card input:checked + .private-session-toggle-switch::after {
        transform: translateX(19px);
    }

    .private-session-panel-head a {
        color: var(--ps-accent);
        text-decoration: none;
        font-weight: 900;
        font-size: 13px;
    }

    .private-session-form-panel.is-disabled {
        display: none;
    }

    .private-session-submit-row {
        display: flex;
        justify-content: flex-start;
        margin-top: 8px;
    }

    .private-session-submit-row .btn-primary {
        min-height: 50px;
        padding-inline: 24px;
    }

    @media (max-width: 760px) {
        .private-session-section-head,
        .private-session-panel-head {
            display: block;
        }

        .private-session-section-head p,
        .private-session-panel-head a {
            display: block;
            margin-top: 8px;
        }

        .private-session-toggle-card {
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    const educationToggle = document.getElementById('useEducationData');
    const educationPanel = document.getElementById('educationPanel');
    const educationControls = document.querySelectorAll('.education-control');
    const centerTeacherSelect = document.getElementById('centerTeacherSelect');
    const hostNameInput = document.getElementById('hostNameInput');

    function syncEducationPanel() {
        const enabled = educationToggle.checked;
        educationPanel.classList.toggle('is-disabled', !enabled);
        educationControls.forEach(control => {
            control.disabled = !enabled;
        });
    }

    educationToggle.addEventListener('change', syncEducationPanel);
    syncEducationPanel();

    centerTeacherSelect?.addEventListener('change', function () {
        if (!educationToggle.checked || hostNameInput.value.trim() !== '') {
            return;
        }

        const selected = this.options[this.selectedIndex];
        hostNameInput.value = selected?.dataset?.name || '';
    });
</script>
@endsection
