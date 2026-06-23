@extends('workspace.layouts.app')

@section('content')
<div class="settings-container workspace-dark-page workspace-clients-dark">
    <div class="workspace-dark-hero">
        <h1 class="workspace-dark-title">{{ __('portal.clients.title') }}</h1>
    </div>

    <div class="card workspace-dark-card" style="margin-bottom: 20px;">
        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h3 style="margin:0 0 5px;">{{ __('portal.clients.log_title') }}</h3>
                <p style="margin:0; color:var(--upwork-muted); font-size:13px;">
                    {{ __('portal.clients.log_subtitle') }}
                </p>
            </div>
            @php
                $activeFilterCount = collect(request()->only(['search', 'from', 'to', 'plan']))
                    ->filter(fn ($value) => filled($value))
                    ->count();
            @endphp
            @if($activeFilterCount > 0)
                <span style="background:var(--upwork-green-soft); color:var(--upwork-green-dark); padding:7px 12px; border-radius:20px; font-size:13px; font-weight:800;">
                    {{ __('portal.clients.filters_count', ['count' => $activeFilterCount]) }}
                </span>
            @endif
        </div>
        <form id="search-form" action="{{ route('workspace.clients.index') }}" method="GET">
            <div class="grid-3" style="gap:14px;">
                <div style="position: relative;">
                    <label for="search-input">{{ __('portal.clients.search') }}</label>
                    <input type="text" id="search-input" name="search" class="form-control" placeholder="{{ __('portal.clients.search_placeholder') }}" value="{{ request('search') }}">
                    <div id="search-loader" style="display: none; position: absolute; left: 15px; top: 42px; color: var(--upwork-muted);">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </div>
                </div>
                <div>
                    <label for="from">{{ __('portal.clients.from') }}</label>
                    <input type="datetime-local" id="from" name="from" class="form-control" value="{{ request('from') }}">
                    <small style="color:var(--upwork-muted);">{{ __('portal.clients.from_hint') }}</small>
                </div>
                <div>
                    <label for="to">{{ __('portal.clients.to') }}</label>
                    <input type="datetime-local" id="to" name="to" class="form-control" value="{{ request('to') }}">
                    <small style="color:var(--upwork-muted);">{{ __('portal.clients.to_hint') }}</small>
                </div>
                <div>
                    <label for="plan">{{ __('portal.clients.plan') }}</label>
                    <select id="plan" name="plan" class="form-control">
                        <option value="">{{ __('portal.clients.all_plans') }}</option>
                        <option value="FREE" @selected(request('plan') === 'FREE')>{{ __('portal.billing.FREE') }}</option>
                        <option value="GLOBAL_SUBSCRIPTION" @selected(request('plan') === 'GLOBAL_SUBSCRIPTION')>{{ __('portal.billing.GLOBAL_SUBSCRIPTION') }}</option>
                        <option value="WORKSPACE_SUBSCRIPTION" @selected(request('plan') === 'WORKSPACE_SUBSCRIPTION')>{{ __('portal.billing.WORKSPACE_SUBSCRIPTION') }}</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; gap:10px; margin-top:4px;">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-filter"></i> {{ __('portal.clients.apply') }}</button>
                <a href="{{ route('workspace.clients.index') }}" class="btn-action" style="padding: 11px 20px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); text-decoration: none; color: var(--upwork-slate); display: inline-flex; align-items: center;">
                    {{ __('portal.clients.clear') }}
                </a>
            </div>
        </form>
    </div>

    <div class="card workspace-dark-card" id="clients-table-container">
        @include('workspace.clients.partials.table', ['clients' => $clients])
    </div>
</div>
@endsection

@section('styles')
<style>
    .workspace-dark-page {
        --wd-surface: #16211b;
        --wd-surface-2: #1d2c24;
        --wd-input: #0f1813;
        --wd-border: #2c3d33;
        --wd-text: #eef3f0;
        --wd-muted: #9db0a4;
        --wd-dim: #6c7e73;
        --wd-accent: #2bd968;
    }

    .workspace-dark-hero {
        margin-bottom: 22px;
        padding: 24px;
        border-radius: var(--radius-md);
        border: 1px solid var(--wd-border);
        background:
            radial-gradient(circle at 10% 20%, rgba(43, 217, 104, .13), transparent 28%),
            linear-gradient(135deg, var(--wd-surface, #16211b) 0%, var(--wd-surface-2, #1d2c24) 100%);
        box-shadow: 0 14px 34px rgba(0, 0, 0, .18);
    }

    .workspace-dark-title {
        margin: 0;
        color: var(--wd-text);
        font-size: 34px;
        line-height: 1.2;
        font-weight: 900;
    }

    .workspace-dark-page .card,
    .workspace-dark-page .workspace-dark-card {
        background: var(--wd-surface) !important;
        color: var(--wd-text) !important;
        border: 1px solid var(--wd-border) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
    }

    .workspace-dark-page h3,
    .workspace-dark-page label,
    .workspace-dark-page td {
        color: var(--wd-text) !important;
    }

    .workspace-dark-page p,
    .workspace-dark-page small,
    .workspace-dark-page .empty-state {
        color: var(--wd-muted) !important;
    }

    .workspace-dark-page .form-control,
    .workspace-dark-page select,
    .workspace-dark-page input {
        background: var(--wd-input) !important;
        border-color: var(--wd-border) !important;
        color: var(--wd-text) !important;
    }

    .workspace-dark-page .form-control::placeholder {
        color: var(--wd-dim);
    }

    .workspace-dark-page table {
        color: var(--wd-text);
    }

    .workspace-dark-page thead tr {
        background: var(--wd-surface-2) !important;
        border-bottom: 2px solid var(--wd-border) !important;
    }

    .workspace-dark-page tbody tr {
        border-bottom: 1px solid var(--wd-border) !important;
    }

    .workspace-dark-page tbody tr:hover {
        background: var(--wd-row-hover, #21322a);
    }

    .workspace-dark-page th {
        color: var(--wd-muted) !important;
    }

    .workspace-dark-page tfoot tr {
        background: rgba(43, 217, 104, .12) !important;
        border-top: 2px solid var(--wd-accent) !important;
    }

    .workspace-dark-page td[style*="var(--upwork-green-dark)"],
    .workspace-dark-page tfoot td[style*="var(--upwork-green-dark)"] {
        color: var(--wd-accent) !important;
    }

    .workspace-dark-page a {
        color: #7bb7ff;
    }

    .workspace-dark-page .btn-action {
        background: var(--wd-input) !important;
        color: var(--wd-text) !important;
        border-color: var(--wd-border) !important;
    }
</style>
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
                    throw new Error(@json(__('portal.clients.apply_error')));
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
