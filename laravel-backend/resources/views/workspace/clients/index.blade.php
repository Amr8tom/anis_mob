@extends('workspace.layouts.app')

@section('content')
<div class="settings-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title" style="margin: 0;">العملاء والزوار</h1>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form action="{{ route('workspace.clients.index') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو رقم الهاتف..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn-action btn-action-primary" style="padding: 12px 24px; border-radius: var(--radius-sm); border: none; color: white; background: var(--upwork-green); cursor: pointer;">
                بحث
            </button>
            @if(request('search'))
                <a href="{{ route('workspace.clients.index') }}" class="btn-action" style="padding: 12px 24px; border-radius: var(--radius-sm); border: 1px solid var(--upwork-border); text-decoration: none; color: var(--upwork-slate); display: inline-flex; align-items: center;">
                    إلغاء
                </a>
            @endif
        </form>
    </div>

    <div class="card">
        @if($clients->count() > 0)
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--upwork-border);">
                        <th style="padding: 12px;">اسم العميل</th>
                        <th style="padding: 12px;">رقم الهاتف</th>
                        <th style="padding: 12px;">عدد الزيارات</th>
                        <th style="padding: 12px;">آخر زيارة</th>
                        <th style="padding: 12px;">التفاصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr style="border-bottom: 1px solid var(--upwork-border);">
                            <td style="padding: 12px; font-weight: bold;">{{ $client->full_name }}</td>
                            <td style="padding: 12px; direction: ltr; text-align: right;">{{ $client->phone_number }}</td>
                            <td style="padding: 12px;">
                                <span style="background: var(--upwork-green-soft); color: var(--upwork-green-dark); padding: 4px 8px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                                    {{ $client->total_visits }}
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                {{ \Carbon\Carbon::parse($client->last_visit)->diffForHumans() }}<br>
                                <small style="color: var(--upwork-muted);">{{ \Carbon\Carbon::parse($client->last_visit)->format('Y-m-d h:i A') }}</small>
                            </td>
                            <td style="padding: 12px;">
                                <a href="{{ route('workspace.clients.show', $client->id) }}" style="color: var(--upwork-blue); text-decoration: none;">عرض السجل</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                {{ $clients->withQueryString()->links() }}
            </div>
        @else
            <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">لا يوجد عملاء حالياً يطابقون بحثك.</p>
        @endif
    </div>
</div>
@endsection
