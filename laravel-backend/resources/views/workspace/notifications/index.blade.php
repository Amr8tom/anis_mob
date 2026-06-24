@extends('workspace.layouts.app')
@section('title', __('portal.notifications.meta_title'))

@section('styles')
<style>
    .notif-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 960px) {
        .notif-grid { grid-template-columns: 1fr; }
        .notif-aside { order: -1; }
    }

    /* Target Selection Styles */
    .target-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 600px) {
        .target-options { grid-template-columns: 1fr; }
    }
    .target-opt {
        position: relative;
        border: 2px solid var(--upwork-border);
        border-radius: var(--radius-md);
        padding: 16px;
        cursor: pointer;
        transition: var(--transition);
        background: var(--upwork-card-bg);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .target-opt:hover {
        border-color: rgba(20,168,0,0.3);
        background: var(--upwork-green-soft);
    }
    .target-opt.active {
        border-color: var(--upwork-green);
        background: var(--upwork-green-soft);
        box-shadow: 0 4px 12px rgba(20,168,0,0.1);
    }
    .target-opt input[type="radio"] {
        display: none;
    }
    .target-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--upwork-bg);
        color: var(--upwork-muted);
        display: grid;
        place-items: center;
        font-size: 18px;
        transition: var(--transition);
        flex-shrink: 0;
    }
    .target-opt.active .target-icon {
        background: var(--upwork-green);
        color: #fff;
    }
    .target-info {
        flex: 1;
    }
    .target-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--upwork-slate);
        margin-bottom: 2px;
    }

    /* Dynamic Containers */
    .dynamic-container {
        display: none;
        animation: fadeIn 0.3s ease;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px dashed var(--upwork-border);
    }
    .dynamic-container.show {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Search & Users Table */
    .search-wrapper {
        position: relative;
        margin-bottom: 20px;
    }
    .search-wrapper i {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        color: var(--upwork-muted);
        font-size: 18px;
    }
    html[dir="ltr"] .search-wrapper i {
        right: auto;
        left: 16px;
    }
    .search-input {
        padding-inline-start: 46px;
        height: 52px;
        font-size: 16px;
        border-radius: 99px;
    }

    .users-table-wrapper {
        border: 1px solid var(--upwork-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--upwork-card-bg);
    }
    .users-table th, .users-table td {
        padding: 14px 16px;
        vertical-align: middle;
    }
    .users-table th {
        background: var(--upwork-bg);
        border-bottom: 2px solid var(--upwork-border);
    }
    .users-table tbody tr {
        cursor: pointer;
    }
    .users-table tbody tr:hover {
        background: var(--upwork-green-soft);
    }
    .device-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
    }
    .device-badge.has-devices {
        background: var(--upwork-success-bg);
        color: var(--upwork-success);
        border: 1px solid rgba(20,168,0,0.2);
    }
    .device-badge.no-devices {
        background: var(--upwork-error-bg);
        color: var(--upwork-error);
        border: 1px solid rgba(223,32,32,0.2);
    }
    .checkbox-custom {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid var(--upwork-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    input[type="checkbox"]:checked + .checkbox-custom {
        background: var(--upwork-green);
        border-color: var(--upwork-green);
        color: #fff;
    }
    input[type="checkbox"] {
        display: none;
    }

    /* Image Upload Styles */
    .image-upload-wrapper {
        border: 2px dashed var(--upwork-input-border);
        border-radius: var(--radius-md);
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        background: var(--upwork-bg);
    }
    .image-upload-wrapper:hover {
        border-color: var(--upwork-green);
        background: var(--upwork-green-soft);
    }
    .image-upload-wrapper.has-image {
        padding: 0;
        border: none;
        overflow: hidden;
    }
    .image-preview-container {
        position: relative;
        width: 100%;
        aspect-ratio: 16/9;
        display: none;
    }
    .image-upload-wrapper.has-image .image-preview-container {
        display: block;
    }
    .image-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .remove-image-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: var(--transition);
        backdrop-filter: blur(4px);
    }
    .remove-image-btn:hover {
        background: var(--upwork-error);
        transform: scale(1.1);
    }
    .upload-placeholder {
        color: var(--upwork-muted);
    }
    .upload-placeholder i {
        font-size: 32px;
        margin-bottom: 12px;
        color: rgba(20,168,0,0.5);
    }
    .upload-placeholder div {
        font-size: 14px;
        font-weight: 600;
    }

    .select2-container .select2-selection--single {
        height: 52px !important;
        border: 1px solid var(--upwork-input-border) !important;
        border-radius: var(--radius-sm) !important;
        background: var(--upwork-card-bg) !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--upwork-slate) !important;
        line-height: normal !important;
        padding-inline-start: 14px !important;
        font-weight: 600;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px !important;
    }
    .select2-dropdown {
        border-color: var(--upwork-green) !important;
        border-radius: var(--radius-sm) !important;
        box-shadow: var(--shadow-md);
        background: var(--upwork-card-bg) !important;
    }
    .select2-results__option {
        padding: 12px 14px !important;
        color: var(--upwork-slate) !important;
    }
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background: var(--upwork-green) !important;
        color: #fff !important;
    }
</style>
<!-- Select2 for Session Dropdown -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<h1 class="page-title">{{ __('portal.notifications.title') }}</h1>
<div class="page-subtitle">{{ __('portal.notifications.subtitle') }}</div>

<form id="notificationForm" action="{{ route('workspace.notifications.send') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="target_type" id="target_type" value="user">

    <div class="notif-grid">
        <!-- Target Selection Column -->
        <div class="notif-main">
            <div class="card">
                <h2 class="card-title"><i class="fa-solid fa-bullseye" style="color:var(--upwork-blue);"></i> {{ __('portal.notifications.target_title') }}</h2>
                
                <div class="target-options">
                    <label class="target-opt active" data-type="user">
                        <div class="target-icon"><i class="fa-solid fa-user-magnifying-glass"></i></div>
                        <div class="target-info">
                            <div class="target-title">{{ __('portal.notifications.target_user') }}</div>
                        </div>
                    </label>

                    <label class="target-opt" data-type="all_visitors">
                        <div class="target-icon"><i class="fa-solid fa-users-viewfinder"></i></div>
                        <div class="target-info">
                            <div class="target-title">{{ __('portal.notifications.target_all_visitors') }}</div>
                        </div>
                    </label>

                    <label class="target-opt" data-type="public_session">
                        <div class="target-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                        <div class="target-info">
                            <div class="target-title">{{ __('portal.notifications.target_public_session') }}</div>
                        </div>
                    </label>

                    <label class="target-opt" data-type="private_session">
                        <div class="target-icon"><i class="fa-solid fa-qrcode"></i></div>
                        <div class="target-info">
                            <div class="target-title">{{ __('portal.notifications.target_private_session') }}</div>
                        </div>
                    </label>
                </div>

                <!-- Specific Users Container -->
                <div id="container-user" class="dynamic-container show">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="userSearchInput" class="form-control search-input" placeholder="{{ __('portal.notifications.search_placeholder') }}" autocomplete="off">
                    </div>

                    <div class="users-table-wrapper" style="display: none;" id="usersTableWrapper">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="users-table table" style="margin-bottom:0;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width: 50px; text-align: center;">
                                            <label style="margin:0; display:inline-block;">
                                                <input type="checkbox" id="selectAllUsers">
                                                <div class="checkbox-custom"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
                                            </label>
                                        </th>
                                        <th>{{ __('portal.notifications.th_name') }}</th>
                                        <th>{{ __('portal.notifications.th_phone') }}</th>
                                        <th>{{ __('portal.notifications.th_device') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Populated via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Session Select Container (used by both public & private) -->
                <div id="container-session" class="dynamic-container">
                    <label for="sessionSelect">{{ __('portal.notifications.choose_session') }}</label>
                    <select name="session_id" id="sessionSelect" class="form-select" style="width: 100%;" disabled>
                        <option value=""></option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Compose Column -->
        <aside class="notif-aside">
            <div class="card" style="position: sticky; top: 24px;">
                <h2 class="card-title"><i class="fa-solid fa-pen-nib" style="color:var(--upwork-green);"></i> {{ __('portal.notifications.compose_title') }}</h2>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="title">{{ __('portal.notifications.field_title') }} <span style="color:var(--upwork-error)">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required style="height: 48px;" placeholder="Ex: Special Offer!">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="body">{{ __('portal.notifications.field_body') }} <span style="color:var(--upwork-error)">*</span></label>
                    <textarea name="body" id="body" class="form-control" required style="height: 120px; resize: none;" placeholder="Message content..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label>{{ __('portal.notifications.field_image') }}</label>
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
                    <div class="image-upload-wrapper" id="imageUploadWrapper" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fa-regular fa-image"></i>
                            <div>{{ __('portal.notifications.image_hint') }}</div>
                        </div>
                        <div class="image-preview-container">
                            <img src="" id="imagePreview" class="image-preview" alt="Preview">
                            <button type="button" class="remove-image-btn" id="removeImageBtn" title="{{ __('portal.notifications.remove_image') }}" onclick="event.stopPropagation(); removeImage();">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn" style="width: 100%; height: 52px; font-size: 16px;">
                    <i class="fa-regular fa-paper-plane"></i> <span>{{ __('portal.notifications.send') }}</span>
                </button>
            </div>
        </aside>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const translations = {
        no_device: "{{ __('portal.notifications.no_device') }}",
        students_count: "{{ __('portal.notifications.students_count') }}",
        unnamed_session: "{{ __('portal.notifications.unnamed_session') }}",
        choose_session: "{{ __('portal.notifications.choose_session') }}"
    };

    // Target Selection Logic
    const targetOpts = document.querySelectorAll('.target-opt');
    const targetTypeInput = document.getElementById('target_type');
    const containerSpecific = document.getElementById('container-user');
    const containerSession = document.getElementById('container-session');
    const sessionSelect = $('#sessionSelect');

    targetOpts.forEach(opt => {
        opt.addEventListener('click', function() {
            // Update UI Active State
            targetOpts.forEach(o => o.classList.remove('active'));
            this.classList.add('active');

            // Update Value
            const type = this.getAttribute('data-type');
            targetTypeInput.value = type;

            // Hide all
            containerSpecific.classList.remove('show');
            containerSession.classList.remove('show');
            sessionSelect.prop('disabled', true);

            // Show relevant
            if (type === 'user') {
                containerSpecific.classList.add('show');
            } else if (type === 'public_session' || type === 'private_session') {
                containerSession.classList.add('show');
                sessionSelect.prop('disabled', false);
                loadSessions(type === 'public_session' ? 'public' : 'private');
            }
        });
    });

    // Initialize Select2
    sessionSelect.select2({
        placeholder: translations.choose_session,
        allowClear: true,
        width: '100%',
        dir: document.documentElement.dir || 'rtl'
    });

    // Load Sessions via AJAX
    function loadSessions(type) {
        sessionSelect.empty().append('<option value=""></option>');
        $.ajax({
            url: "{{ route('workspace.notifications.sessions') }}",
            data: { type: type },
            dataType: 'json',
            success: function(data) {
                data.forEach(function(session) {
                    const title = session.title || translations.unnamed_session;
                    const text = title + ' — ' + session.students_count + ' ' + translations.students_count.replace(':count', '');
                    sessionSelect.append(new Option(text, session.id, false, false));
                });
            }
        });
    }

    // User Search Logic
    let searchTimeout;
    const searchInput = document.getElementById('userSearchInput');
    const usersTableWrapper = document.getElementById('usersTableWrapper');
    const usersTableBody = document.getElementById('usersTableBody');
    const selectAllCb = document.getElementById('selectAllUsers');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        searchTimeout = setTimeout(() => {
            fetchUsers(query);
        }, 400);
    });

    function fetchUsers(query) {
        $.ajax({
            url: "{{ route('workspace.notifications.search') }}",
            data: { q: query },
            dataType: 'json',
            success: function(data) {
                usersTableBody.innerHTML = '';
                if (data.length > 0) {
                    usersTableWrapper.style.display = 'block';
                    data.forEach(user => {
                        const tr = document.createElement('tr');
                        const hasDevice = user.device_count > 0;
                        const deviceBadge = hasDevice
                            ? `<span class="device-badge has-devices"><i class="fa-solid fa-mobile-screen"></i> ${user.device_count}</span>`
                            : `<span class="device-badge no-devices"><i class="fa-solid fa-mobile-screen"></i> ${translations.no_device}</span>`;

                        tr.innerHTML = `
                            <td style="text-align:center;" onclick="event.stopPropagation()">
                                <label style="margin:0; display:inline-block; cursor:pointer;">
                                    <input type="checkbox" name="user_ids[]" value="${user.id}" class="user-checkbox">
                                    <div class="checkbox-custom"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
                                </label>
                            </td>
                            <td><div style="font-weight:700; color:var(--upwork-slate);">${user.name}</div></td>
                            <td dir="ltr" style="text-align:end;">${user.phone_number}</td>
                            <td>${deviceBadge}</td>
                        `;

                        // Whole-row toggles the checkbox (every user is selectable).
                        tr.addEventListener('click', function(e) {
                            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL' && !e.target.closest('.checkbox-custom')) {
                                const cb = this.querySelector('.user-checkbox');
                                cb.checked = !cb.checked;
                                updateSelectAllState();
                            }
                        });

                        usersTableBody.appendChild(tr);
                    });
                    
                    // Attach listener to new checkboxes
                    document.querySelectorAll('.user-checkbox').forEach(cb => {
                        cb.addEventListener('change', updateSelectAllState);
                    });
                    updateSelectAllState();

                } else {
                    usersTableWrapper.style.display = 'none';
                }
            }
        });
    }

    selectAllCb.addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.checked = isChecked;
        });
    });

    function updateSelectAllState() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        
        if (checkboxes.length === 0) {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = false;
        } else if (checkedBoxes.length === checkboxes.length) {
            selectAllCb.checked = true;
            selectAllCb.indeterminate = false;
        } else if (checkedBoxes.length > 0) {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = true;
        } else {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = false;
        }
    }

    // Image Upload Logic
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const imageUploadWrapper = document.getElementById('imageUploadWrapper');

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imageUploadWrapper.classList.add('has-image');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    function removeImage() {
        imageInput.value = '';
        imagePreview.src = '';
        imageUploadWrapper.classList.remove('has-image');
    }

    // Drag and Drop Image
    imageUploadWrapper.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageUploadWrapper.style.borderColor = 'var(--upwork-green)';
        imageUploadWrapper.style.background = 'var(--upwork-green-soft)';
    });
    imageUploadWrapper.addEventListener('dragleave', (e) => {
        e.preventDefault();
        imageUploadWrapper.style.borderColor = '';
        imageUploadWrapper.style.background = '';
    });
    imageUploadWrapper.addEventListener('drop', (e) => {
        e.preventDefault();
        imageUploadWrapper.style.borderColor = '';
        imageUploadWrapper.style.background = '';
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            imageInput.files = e.dataTransfer.files;
            // Trigger change event
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    });

    // Form Submit handling (Prevent double submission & show loading state)
    const form = document.getElementById('notificationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        
        // Validation
        const targetType = targetTypeInput.value;
        if (targetType === 'user') {
            const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
            if (selectedCount === 0) {
                e.preventDefault();
                alert("Please select at least one user.");
                return;
            }
        } else if (targetType === 'public_session' || targetType === 'private_session') {
            if (!sessionSelect.val()) {
                e.preventDefault();
                alert("Please select a session.");
                return;
            }
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>{{ __("portal.notifications.sending") }}</span>';
    });

    // Fetch initial users list
    $(document).ready(function() {
        fetchUsers('');
    });
</script>
@endsection
