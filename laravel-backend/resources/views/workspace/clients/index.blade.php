@extends('workspace.layouts.app')

@section('content')
<div class="settings-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title" style="margin: 0;">العملاء والزوار</h1>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h3 style="margin:0 0 5px;">سجل الزوار</h3>
                <p style="margin:0; color:var(--upwork-muted); font-size:13px;">
                    النتائج والإجماليات أدناه تتغير حسب الاسم أو رقم الهاتف، الفترة، والباقة.
                </p>
            </div>
            @php
                $activeFilterCount = collect(request()->only(['search', 'from', 'to', 'plan']))
                    ->filter(fn ($value) => filled($value))
                    ->count();
            @endphp
            @if($activeFilterCount > 0)
                <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:7px 12px; border-radius:20px; font-size:13px; font-weight:800;">
                    {{ $activeFilterCount }} محدد
                </span>
            @endif
        </div>
        <form id="search-form" action="{{ route('workspace.clients.index') }}" method="GET">
            <div class="grid-3" style="gap:14px;">
                <div style="position: relative;">
                    <label for="search-input">الاسم أو رقم الهاتف</label>
                    <input type="text" id="search-input" name="search" class="form-control" placeholder="ابحث بالاسم أو الهاتف..." value="{{ request('search') }}">
                    <div id="search-loader" style="display: none; position: absolute; left: 15px; top: 42px; color: var(--upwork-muted);">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </div>
                </div>
                <div>
                    <label for="from">من تاريخ ووقت</label>
                    <input type="datetime-local" id="from" name="from" class="form-control" value="{{ request('from') }}">
                    <small style="color:var(--upwork-muted);">يشمل الزيارات من بداية الدقيقة المحددة.</small>
                </div>
                <div>
                    <label for="to">إلى تاريخ ووقت</label>
                    <input type="datetime-local" id="to" name="to" class="form-control" value="{{ request('to') }}">
                    <small style="color:var(--upwork-muted);">يشمل الدقيقة المحددة بالكامل.</small>
                </div>
                <div>
                    <label for="plan">الباقة</label>
                    <select id="plan" name="plan" class="form-control">
                        <option value="">كل الباقات</option>
                        <option value="FREE" @selected(request('plan') === 'FREE')>مجاني</option>
                        <option value="GLOBAL_SUBSCRIPTION" @selected(request('plan') === 'GLOBAL_SUBSCRIPTION')>عالمي</option>
                        <option value="WORKSPACE_SUBSCRIPTION" @selected(request('plan') === 'WORKSPACE_SUBSCRIPTION')>اشتراك مساحة</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:10px; margin-top:4px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> تطبيق</button>
                <a href="{{ route('workspace.clients.index') }}" class="btn-action" style="padding: 11px 20px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); text-decoration: none; color: var(--upwork-slate); display: inline-flex; align-items: center;">
                    مسح
                </a>
            </div>
        </form>
    </div>

    <div class="card" id="clients-table-container">
        @include('workspace.clients.partials.table', ['clients' => $clients])
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const tableContainer = document.getElementById('clients-table-container');
    const searchLoader = document.getElementById('search-loader');

    let debounceTimer;

    function refreshResults() {
        const form = document.getElementById('search-form');
        if (!form.reportValidity()) {
            return;
        }

        clearTimeout(debounceTimer);
        searchLoader.style.display = 'block';

        debounceTimer = setTimeout(() => {
            const formData = new FormData(form);
            const url = new URL(form.action);
            formData.forEach((value, key) => value && url.searchParams.set(key, value));

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('تعذر التطبيق.');
                }

                return response.text();
            })
            .then(html => {
                tableContainer.innerHTML = html;
                searchLoader.style.display = 'none';

                // Update URL without reloading page
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                searchLoader.style.display = 'none';
            });
        }, 300);
    }

    searchInput.addEventListener('input', refreshResults);

    document.querySelectorAll('#search-form select, #search-form input[type="datetime-local"]').forEach((field) => {
        field.addEventListener('change', refreshResults);
    });

    // Handle AJAX pagination
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination-links a')) {
            e.preventDefault();
            const url = e.target.closest('.pagination-links a').href;

            searchLoader.style.display = 'block';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                searchLoader.style.display = 'none';
                window.history.pushState({}, '', url);
                window.scrollTo(0, 0);
            });
        }
    });
});
</script>
@endsection
