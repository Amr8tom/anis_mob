@extends('admin.layout.app')

@section('title', 'مساحات العمل | بوابة الإدارة')
@section('header', 'إدارة مساحات العمل')

@section('content')

@if(session('success'))
<div class="card" style="border-right: 4px solid var(--upwork-green); margin-bottom: 20px; color: var(--upwork-green); font-weight: 600;">
    {{ session('success') }}
</div>
@endif

{{-- Pending registrations awaiting review --}}
@if(!$trashed && $pendingWorkspaces->isNotEmpty())
<div class="card" style="margin-bottom: 20px; border-right: 4px solid #b76e00;">
    <h3 style="margin-bottom: 16px;"><i class="fa-solid fa-hourglass-half" style="color:#b76e00;"></i> طلبات تسجيل بانتظار المراجعة ({{ $pendingWorkspaces->count() }})</h3>
    <div class="table-responsive">
        <table>
            <thead style="background-color: var(--upwork-bg);">
                <tr><th>مساحة العمل</th><th>المالك</th><th>الهاتف</th><th style="text-align:left;">الإجراء</th></tr>
            </thead>
            <tbody>
                @foreach($pendingWorkspaces as $pending)
                <tr>
                    <td><div style="font-weight:700;">{{ $pending->name }}</div>
                        <div style="font-size:13px; color:var(--upwork-muted);">{{ $pending->address }}</div></td>
                    <td>{{ $pending->owner?->full_name ?? '—' }}</td>
                    <td style="font-family:monospace;">{{ $pending->owner?->phone_number ?? '—' }}</td>
                    <td style="text-align:left;">
                        <a href="{{ route('admin.workspaces.show', $pending) }}" style="color:#b76e00; font-weight:700; text-decoration:none;">مراجعة الطلب</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.workspaces.index') }}" class="btn-primary" style="{{ $trashed ? 'background:transparent; color:var(--upwork-slate); border:1px solid var(--upwork-border);' : '' }}">النشطة</a>
        <a href="{{ route('admin.workspaces.index', ['trashed' => 1]) }}" class="btn-primary" style="{{ $trashed ? '' : 'background:transparent; color:var(--upwork-slate); border:1px solid var(--upwork-border);' }}">المحذوفة</a>
    </div>
    <a class="btn-primary" href="{{ route('admin.workspaces.create') }}">إضافة مساحة عمل</a>
</div>
<div class="card" style="padding: 0;">
    <div class="table-responsive">
        <table>
            <thead style="background-color: var(--upwork-bg);">
                <tr>
                    <th>مساحة العمل</th>
                    <th>الحالة</th>
                    <th>السعة</th>
                    <th style="text-align: left;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workspaces as $workspace)
                <tr style="transition: var(--transition);">
                    <td>
                        <div style="font-weight: 700; color: var(--upwork-slate);">{{ $workspace->name }}</div>
                        <div style="font-size: 13px; color: var(--upwork-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $workspace->address }}</div>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
                            {{ $workspace->isApproved() ? 'background:var(--upwork-success-bg); color:var(--upwork-success);' : ($workspace->isSuspended() ? 'background:#fff7e6; color:#b76e00;' : 'background:var(--upwork-error-bg); color:var(--upwork-error);') }}">
                            {{ $workspace->lifecycle_status->label() }}
                        </span>
                        @unless($workspace->is_active)
                            <span style="margin-right:6px; font-size:11px; color:var(--upwork-muted);">(غير مفعّلة)</span>
                        @endunless
                    </td>
                    <td style="font-family: monospace;">
                        {{ $workspace->manual_occupancy }} / {{ $workspace->capacity }}
                    </td>
                    <td style="text-align: left;">
                        @if($trashed)
                            <form method="POST" action="{{ route('admin.workspaces.restore', $workspace->id) }}" onsubmit="return confirm('استعادة مساحة العمل؟');">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:var(--upwork-green); font-weight:600; cursor:pointer;">استعادة</button>
                            </form>
                        @else
                            <a href="{{ route('admin.workspaces.show', $workspace) }}" style="color: var(--upwork-green); font-weight: 600; text-decoration: none;">عرض التفاصيل وQR</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: var(--upwork-muted);">
                        لم يتم العثور على مساحات عمل.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($workspaces->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--upwork-border);">
        {{ $workspaces->links() }}
    </div>
    @endif
</div>
@endsection
