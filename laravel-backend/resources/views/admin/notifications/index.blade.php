@extends('admin.layout.app')

@section('title', 'الإشعارات | بوابة الإدارة')
@section('header', 'إرسال إشعار لكل المستخدمين')

@section('content')
<div class="alert alert-success">
    <i class="fa-solid fa-circle-info"></i>
    <div>هذا الإشعار يُرسَل كإشعار Push للمستخدمين الذين لديهم جهاز مسجّل فقط. لا يشمل زوار walk-in.</div>
</div>

<div class="grid-4">
    <div class="card" style="grid-column: span 1;">
        <h3 class="card-title" style="font-size: 16px;"><i class="fa-solid fa-users" style="color: var(--upwork-blue);"></i> كل المستخدمين</h3>
        <div style="font-size: 38px; font-weight: 900; color: var(--upwork-green); line-height: 1.1;">{{ number_format($audienceCount) }}</div>
        <div style="font-size: 13px; color: var(--upwork-muted); margin-top: 6px;">إجمالي مستخدمي التطبيق الذين لديهم جهاز مسجّل.</div>
    </div>

    <div class="card" style="grid-column: span 3;">
        <h3 class="card-title" style="font-size: 16px;"><i class="fa-solid fa-paper-plane" style="color: var(--upwork-green);"></i> إنشاء الإشعار</h3>

        <form action="{{ route('admin.notifications.send') }}" method="POST" enctype="multipart/form-data" id="adminNotifForm"
              onsubmit="return confirm('هل تريد إرسال الإشعار للجمهور المحدد؟');">
            @csrf

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">الجمهور المستهدف <span style="color: var(--upwork-error);">*</span></label>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 12px;">
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 14px; cursor: pointer;">
                        <input type="radio" name="target_type" value="all_users" {{ old('target_type', 'all_users') === 'all_users' ? 'checked' : '' }} onchange="toggleWorkspaceSelect()"> كل المستخدمين
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 14px; cursor: pointer;">
                        <input type="radio" name="target_type" value="workspace_visitors" {{ old('target_type') === 'workspace_visitors' ? 'checked' : '' }} onchange="toggleWorkspaceSelect()"> زوار مساحة معيّنة
                    </label>
                </div>
                <div id="workspaceSelectWrapper" style="display: none;">
                    <select name="workspace_id" id="workspaceSelect" class="form-select">
                        <option value="" disabled {{ old('workspace_id') ? '' : 'selected' }}>-- اختر المساحة --</option>
                        @foreach($workspaces as $workspace)
                            <option value="{{ $workspace->id }}" {{ old('workspace_id') === $workspace->id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                        @endforeach
                    </select>
                    <div style="font-size: 12px; color: var(--upwork-muted); margin-top: 6px;">سيتم الإرسال للمستخدمين المسجّلين الذين زاروا هذه المساحة فقط.</div>
                </div>
                @error('target_type')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
                @error('workspace_id')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">العنوان <span style="color: var(--upwork-error);">*</span></label>
                <input type="text" name="title" class="form-input" maxlength="120" value="{{ old('title') }}" required placeholder="مثال: تحديث جديد في التطبيق">
                @error('title')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">نص الإشعار <span style="color: var(--upwork-error);">*</span></label>
                <textarea name="body" class="form-input" rows="4" maxlength="1000" required style="resize: vertical;" placeholder="اكتب محتوى الرسالة...">{{ old('body') }}</textarea>
                @error('body')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">صورة (اختياري)</label>
                <input type="file" name="image" id="adminNotifImage" accept="image/*">
                <img id="adminNotifPreview" src="" alt="" style="display: none; margin-top: 12px; max-height: 180px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border);">
                @error('image')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-primary" {{ $audienceCount === 0 ? 'disabled' : '' }}>
                <i class="fa-solid fa-paper-plane"></i> إرسال للجميع
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleWorkspaceSelect() {
        const isWorkspace = document.querySelector('input[name="target_type"]:checked').value === 'workspace_visitors';
        const wrapper = document.getElementById('workspaceSelectWrapper');
        const select = document.getElementById('workspaceSelect');
        wrapper.style.display = isWorkspace ? 'block' : 'none';
        // Only require/submit the workspace when that mode is active.
        select.disabled = !isWorkspace;
        select.required = isWorkspace;
    }
    toggleWorkspaceSelect();

    document.getElementById('adminNotifImage').addEventListener('change', function () {
        const preview = document.getElementById('adminNotifPreview');
        if (this.files && this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection
