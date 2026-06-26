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
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">قالب جاهز</label>
                <select id="adminTemplateSelect" class="form-select">
                    <option value="">-- اختر قالبًا --</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">نوع الإشعار</label>
                <select name="notification_category" id="adminNotificationCategory" class="form-select">
                    <option value="workspace_updates" {{ old('notification_category', 'workspace_updates') === 'workspace_updates' ? 'selected' : '' }}>تحديثات المساحة</option>
                    <option value="session_reminders" {{ old('notification_category') === 'session_reminders' ? 'selected' : '' }}>تذكيرات الجلسات</option>
                    <option value="subscription_alerts" {{ old('notification_category') === 'subscription_alerts' ? 'selected' : '' }}>تنبيهات الاشتراكات</option>
                    <option value="offers_marketing" {{ old('notification_category') === 'offers_marketing' ? 'selected' : '' }}>عروض وتسويق</option>
                </select>
                @error('notification_category')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">العنوان <span style="color: var(--upwork-error);">*</span></label>
                <input type="text" name="title" id="adminNotifTitle" class="form-input" maxlength="120" value="{{ old('title') }}" required placeholder="مثال: تحديث جديد في التطبيق">
                @error('title')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">نص الإشعار <span style="color: var(--upwork-error);">*</span></label>
                <textarea name="body" id="adminNotifBody" class="form-input" rows="4" maxlength="1000" required style="resize: vertical;" placeholder="اكتب محتوى الرسالة...">{{ old('body') }}</textarea>
                @error('body')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">لغة الإشعار</label>
                <select name="locale" id="adminNotifLocale" class="form-select">
                    <option value="ar" {{ old('locale', 'ar') === 'ar' ? 'selected' : '' }}>العربية</option>
                    <option value="en" {{ old('locale') === 'en' ? 'selected' : '' }}>English</option>
                    <option value="tr" {{ old('locale') === 'tr' ? 'selected' : '' }}>Türkçe</option>
                </select>
                @error('locale')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">موعد الإرسال (اختياري)</label>
                <input type="datetime-local" name="scheduled_at" class="form-input" value="{{ old('scheduled_at') }}">
                <div style="font-size: 12px; color: var(--upwork-muted); margin-top: -8px;">اتركه فارغًا للإرسال الآن. لو الوقت داخل ساعات الهدوء سيتم تأجيله تلقائيًا لأول وقت مسموح.</div>
                @error('scheduled_at')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: var(--upwork-slate); margin-bottom: 8px;">صورة (اختياري)</label>
                <input type="file" name="image" id="adminNotifImage" accept="image/*">
                <img id="adminNotifPreview" src="" alt="" style="display: none; margin-top: 12px; max-height: 180px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border);">
                @error('image')<div style="color: var(--upwork-error); font-size: 13px; margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom: 20px; padding: 16px; border: 1px solid var(--upwork-border); border-radius: var(--radius-md); background: var(--upwork-bg);">
                <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 12px;">
                    <div style="font-weight: 900; color: var(--upwork-slate);">
                        <i class="fa-solid fa-chart-simple" style="color: var(--upwork-green);"></i> معاينة الجمهور قبل الإرسال
                    </div>
                    <button type="button" class="btn-secondary" id="adminPreviewBtn">
                        <i class="fa-solid fa-rotate"></i> حساب الجمهور
                    </button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px;">
                    <div><strong id="adminPreviewAudience">-</strong><div style="font-size: 12px; color: var(--upwork-muted);">داخل الجمهور</div></div>
                    <div><strong id="adminPreviewRegistered">-</strong><div style="font-size: 12px; color: var(--upwork-muted);">مستخدمون مسجلون</div></div>
                    <div><strong id="adminPreviewReachable">-</strong><div style="font-size: 12px; color: var(--upwork-muted);">يمكن الوصول لهم</div></div>
                    <div><strong id="adminPreviewDevices">-</strong><div style="font-size: 12px; color: var(--upwork-muted);">أجهزة نشطة</div></div>
                    <div><strong id="adminPreviewSkipped">-</strong><div style="font-size: 12px; color: var(--upwork-muted);">سيتم تجاوزهم</div></div>
                </div>
                <div id="adminPreviewHelp" style="font-size: 12px; color: var(--upwork-muted); margin-top: 10px;">المعاينة توضح من سيصل له الإشعار فعليًا ومن سيتم تجاوزه.</div>
            </div>

            <button type="submit" class="btn-primary" {{ $audienceCount === 0 ? 'disabled' : '' }}>
                <i class="fa-solid fa-paper-plane"></i> إرسال للجميع
            </button>
        </form>
    </div>
</div>

<div class="card" style="margin-top: 24px;">
    <h3 class="card-title" style="font-size: 16px;"><i class="fa-solid fa-clock-rotate-left" style="color: var(--upwork-green);"></i> سجل آخر حملات الإشعارات</h3>

    @if($campaigns->isEmpty())
        <div style="color: var(--upwork-muted); font-size: 14px;">لا توجد حملات إشعارات بعد.</div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الرسالة</th>
                        <th>الجمهور</th>
                        <th>الحالة</th>
                        <th>النتائج</th>
                        <th>التفاعل</th>
                        <th>وقت الإنشاء</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td>
                                <div style="font-weight: 800; color: var(--upwork-slate);">{{ $campaign->title }}</div>
                                <div style="font-size: 12px; color: var(--upwork-muted); max-width: 520px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $campaign->body }}</div>
                            </td>
                            <td>{{ $campaign->target_type === 'all_users' ? 'كل مستخدمي التطبيق' : 'زوار مساحة معيّنة' }}</td>
                            <td>{{ $campaign->status }}</td>
                            <td dir="ltr" style="text-align: start;">
                                {{ number_format($campaign->sent_count) }} / {{ number_format($campaign->targeted_count) }}
                                @if($campaign->failed_count > 0 || $campaign->skipped_count > 0)
                                    <span style="color: var(--upwork-muted); font-size: 12px;">
                                        ({{ number_format($campaign->failed_count) }} failed,
                                        {{ number_format($campaign->skipped_count) }} skipped)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>{{ number_format($campaign->opened_count) }} فتح</div>
                                <div style="font-size: 12px; color: var(--upwork-muted);">{{ number_format($campaign->clicked_count) }} ضغط</div>
                            </td>
                            <td>
                                <div>{{ $campaign->created_at?->diffForHumans() }}</div>
                                <div style="font-size: 12px; color: var(--upwork-muted);">{{ $campaign->created_at?->format('Y-m-d H:i') }}</div>
                                @if($campaign->scheduled_at)
                                    <div style="font-size: 12px; color: var(--upwork-muted);">موعد الإرسال: {{ $campaign->scheduled_at->format('Y-m-d H:i') }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.notifications.show', $campaign) }}" class="btn-primary" style="padding: 7px 14px; font-size: 13px;">التفاصيل</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    const adminNotificationTemplates = @json($templates);
    const adminTemplateSelect = document.getElementById('adminTemplateSelect');
    const adminLocaleSelect = document.getElementById('adminNotifLocale');
    const adminCategorySelect = document.getElementById('adminNotificationCategory');
    const adminTitleInput = document.getElementById('adminNotifTitle');
    const adminBodyInput = document.getElementById('adminNotifBody');

    function populateAdminTemplates() {
        const locale = adminLocaleSelect.value || 'ar';
        const templates = adminNotificationTemplates[locale] || adminNotificationTemplates.ar || [];
        adminTemplateSelect.innerHTML = '<option value="">-- اختر قالبًا --</option>';
        templates.forEach(template => {
            const option = document.createElement('option');
            option.value = template.key;
            option.textContent = template.label;
            option.dataset.title = template.title;
            option.dataset.body = template.body;
            adminTemplateSelect.appendChild(option);
        });
    }

    adminLocaleSelect.addEventListener('change', populateAdminTemplates);
    adminTemplateSelect.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        if (!option || !option.value) {
            return;
        }

        adminTitleInput.value = option.dataset.title || '';
        adminBodyInput.value = option.dataset.body || '';
        adminCategorySelect.value = categoryForAdminTemplate(option.value);
        resetAdminPreview();
    });
    populateAdminTemplates();

    function categoryForAdminTemplate(templateKey) {
        if (templateKey === 'special_offer') {
            return 'offers_marketing';
        }

        if (templateKey === 'session_reminder') {
            return 'session_reminders';
        }

        if (templateKey === 'subscription_expiry' || templateKey === 'low_hours') {
            return 'subscription_alerts';
        }

        return 'workspace_updates';
    }

    function toggleWorkspaceSelect() {
        const isWorkspace = document.querySelector('input[name="target_type"]:checked').value === 'workspace_visitors';
        const wrapper = document.getElementById('workspaceSelectWrapper');
        const select = document.getElementById('workspaceSelect');
        wrapper.style.display = isWorkspace ? 'block' : 'none';
        // Only require/submit the workspace when that mode is active.
        select.disabled = !isWorkspace;
        select.required = isWorkspace;
        resetAdminPreview();
    }
    toggleWorkspaceSelect();

    function resetAdminPreview() {
        ['adminPreviewAudience', 'adminPreviewRegistered', 'adminPreviewReachable', 'adminPreviewDevices', 'adminPreviewSkipped'].forEach(id => {
            document.getElementById(id).textContent = '-';
        });
    }

    function setAdminPreview(data) {
        document.getElementById('adminPreviewAudience').textContent = Number(data.audience_count || 0).toLocaleString();
        document.getElementById('adminPreviewRegistered').textContent = Number(data.registered_users_count || 0).toLocaleString();
        document.getElementById('adminPreviewReachable').textContent = Number(data.reachable_users_count || 0).toLocaleString();
        document.getElementById('adminPreviewDevices').textContent = Number(data.device_tokens_count || 0).toLocaleString();
        document.getElementById('adminPreviewSkipped').textContent = Number(data.skipped_count || 0).toLocaleString();
    }

    document.getElementById('workspaceSelect').addEventListener('change', resetAdminPreview);
    document.getElementById('adminPreviewBtn').addEventListener('click', function () {
        const button = this;
        const targetType = document.querySelector('input[name="target_type"]:checked').value;
        const workspaceId = document.getElementById('workspaceSelect').value;
        const help = document.getElementById('adminPreviewHelp');

        if (targetType === 'workspace_visitors' && !workspaceId) {
            alert('اختر المساحة أولًا.');
            return;
        }

        button.disabled = true;
        help.textContent = 'جاري حساب الجمهور...';

        fetch("{{ route('admin.notifications.preview') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                target_type: targetType,
                workspace_id: workspaceId || null,
                notification_category: adminCategorySelect.value
            })
        })
            .then(response => response.ok ? response.json() : Promise.reject())
            .then(data => {
                setAdminPreview(data);
                help.textContent = 'المعاينة توضح من سيصل له الإشعار فعليًا ومن سيتم تجاوزه.';
            })
            .catch(() => {
                help.textContent = 'تعذر حساب الجمهور الآن.';
            })
            .finally(() => {
                button.disabled = false;
            });
    });

    document.getElementById('adminNotifImage').addEventListener('change', function () {
        const preview = document.getElementById('adminNotifPreview');
        if (this.files && this.files[0]) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
    adminCategorySelect.addEventListener('change', resetAdminPreview);
</script>
@endsection
