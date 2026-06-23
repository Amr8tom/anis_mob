@extends('workspace.layouts.app')

@section('title', __('portal.education.meta_title'))

@section('content')
<div class="education-page workspace-dark-page">
    <div class="education-hero">
        <div>
            <h1 class="education-title">{{ __('portal.education.title') }}</h1>
            <p class="education-subtitle">{{ __('portal.education.subtitle') }}</p>
        </div>
    </div>

    <div class="education-grid">
        <section class="card education-card education-wide">
            <div class="education-card-head">
                <div>
                    <h3><i class="fa-solid fa-person-chalkboard"></i> {{ __('portal.education.teachers.title') }}</h3>
                    <p>{{ __('portal.education.teachers.subtitle') }}</p>
                </div>
            </div>

            <form action="{{ route('workspace.education.teachers.store') }}" method="POST" class="education-inline-form">
                @csrf
                <div>
                    <label>{{ __('portal.education.teachers.name') }}</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label>{{ __('portal.education.teachers.phone') }}</label>
                    <input name="phone_number" class="form-control" value="{{ old('phone_number') }}" placeholder="{{ __('portal.education.teachers.optional') }}">
                </div>
                <div>
                    <label>{{ __('portal.education.teachers.notes') }}</label>
                    <input name="notes" class="form-control" value="{{ old('notes') }}" placeholder="{{ __('portal.education.teachers.optional') }}">
                </div>
                <button class="btn-primary" type="submit"><i class="fa-solid fa-plus"></i> {{ __('portal.education.teachers.add') }}</button>
            </form>

            @if($teachers->isEmpty())
                <p class="education-empty">{{ __('portal.education.teachers.empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="education-table">
                        <thead>
                            <tr>
                                <th>{{ __('portal.education.teachers.th_name') }}</th>
                                <th>{{ __('portal.education.teachers.th_phone') }}</th>
                                <th>{{ __('portal.education.teachers.th_notes') }}</th>
                                <th>{{ __('portal.education.teachers.th_status') }}</th>
                                <th>{{ __('portal.education.teachers.th_action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->name }}</td>
                                    <td style="direction:ltr; text-align:right;">{{ $teacher->phone_number ?? '—' }}</td>
                                    <td>{{ $teacher->notes ?? '—' }}</td>
                                    <td><span class="education-status {{ $teacher->is_active ? 'is-active' : 'is-paused' }}">{{ $teacher->is_active ? __('portal.education.status_active') : __('portal.education.status_paused') }}</span></td>
                                    <td>
                                        <form action="{{ route('workspace.education.teachers.toggle', $teacher) }}" method="POST">
                                            @csrf
                                            <button class="education-link-btn" type="submit">{{ $teacher->is_active ? __('portal.education.deactivate') : __('portal.education.activate') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:14px;">{{ $teachers->links('vendor.pagination.upwork') }}</div>
            @endif
        </section>

        <section class="card education-card">
            <h3><i class="fa-solid fa-book-open"></i> {{ __('portal.education.subjects.title') }}</h3>
            <p>{{ __('portal.education.subjects.hint') }}</p>
            <form action="{{ route('workspace.education.subjects.store') }}" method="POST">
                @csrf
                <label>{{ __('portal.education.subjects.name') }}</label>
                <input name="name" class="form-control" required>
                <button class="btn-primary" type="submit"><i class="fa-solid fa-plus"></i> {{ __('portal.education.subjects.add') }}</button>
            </form>
            @include('workspace.education.partials.simple-list', [
                'items' => $subjects,
                'routeName' => 'workspace.education.subjects.toggle',
                'empty' => __('portal.education.subjects.empty'),
            ])
        </section>

        <section class="card education-card">
            <h3><i class="fa-solid fa-layer-group"></i> {{ __('portal.education.grades.title') }}</h3>
            <p>{{ __('portal.education.grades.hint') }}</p>
            <form action="{{ route('workspace.education.grade-levels.store') }}" method="POST">
                @csrf
                <label>{{ __('portal.education.grades.name') }}</label>
                <input name="name" class="form-control" required>
                <button class="btn-primary" type="submit"><i class="fa-solid fa-plus"></i> {{ __('portal.education.grades.add') }}</button>
            </form>
            @include('workspace.education.partials.simple-list', [
                'items' => $gradeLevels,
                'routeName' => 'workspace.education.grade-levels.toggle',
                'empty' => __('portal.education.grades.empty'),
            ])
        </section>
    </div>
</div>
@endsection

@section('styles')
<style>
    .education-page {
        --edu-surface: #16211b;
        --edu-surface-2: #1d2c24;
        --edu-input: #0f1813;
        --edu-border: #2c3d33;
        --edu-text: #eef3f0;
        --edu-muted: #9db0a4;
        --edu-accent: #2bd968;
    }

    .education-hero {
        margin-bottom: 22px;
        padding: 24px;
        border-radius: var(--radius-md);
        border: 1px solid var(--edu-border);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .13), transparent 28%),
            linear-gradient(135deg, var(--edu-surface, #16211b) 0%, var(--edu-surface-2, #1d2c24) 100%);
        color: var(--edu-text);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .education-title {
        margin: 0 0 8px;
        color: var(--edu-text);
        font-size: 34px;
        font-weight: 900;
    }

    .education-subtitle,
    .education-card p {
        color: var(--edu-muted);
        margin: 0;
        font-weight: 700;
    }

    .education-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .education-wide {
        grid-column: 1 / -1;
    }

    .education-card {
        background: var(--edu-surface) !important;
        color: var(--edu-text) !important;
        border: 1px solid var(--edu-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .education-card h3 {
        margin: 0 0 8px;
        color: var(--edu-text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .education-card-head {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 14px;
    }

    .education-inline-form {
        display: grid;
        grid-template-columns: 1.1fr .9fr 1.2fr auto;
        align-items: end;
        gap: 12px;
        margin: 18px 0;
    }

    .education-page label {
        color: var(--edu-muted);
    }

    .education-page .form-control {
        background: var(--edu-input) !important;
        border-color: var(--edu-border) !important;
        color: var(--edu-text) !important;
    }

    .education-table {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
        color: var(--edu-text);
    }

    .education-table th,
    .education-table td {
        padding: 11px 10px;
        border-bottom: 1px solid var(--edu-border);
    }

    .education-table thead tr {
        background: var(--edu-surface-2);
    }

    .education-table th {
        color: var(--edu-muted);
        font-weight: 900;
    }

    .education-simple-list {
        margin-top: 16px;
        display: grid;
        gap: 8px;
    }

    .education-simple-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid var(--edu-border);
        border-radius: var(--radius-sm);
        background: var(--edu-input);
    }

    .education-status {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
    }

    .education-status.is-active {
        color: #7ef7a4;
        background: rgba(43, 217, 104, .12);
    }

    .education-status.is-paused {
        color: #fecaca;
        background: rgba(185, 28, 28, .16);
    }

    .education-link-btn {
        border: 0;
        background: transparent;
        color: #7ef7a4;
        font-family: inherit;
        font-weight: 900;
        cursor: pointer;
    }

    .education-empty {
        margin-top: 16px !important;
        padding: 20px;
        border: 1px dashed var(--edu-border);
        border-radius: var(--radius-sm);
        text-align: center;
    }

    @media (max-width: 900px) {
        .education-grid,
        .education-inline-form {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
