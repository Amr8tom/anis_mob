@extends('workspace.layouts.app')

@section('content')
<div class="settings-container">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('workspace.clients.index') }}" style="color: var(--upwork-green); text-decoration: none; font-weight: bold;">&rarr; العودة للعملاء</a>
    </div>

    <div class="card" style="margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
        <div style="width: 80px; height: 80px; background: var(--upwork-green-soft); color: var(--upwork-green-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold;">
            {{ mb_substr($client->full_name, 0, 1) }}
        </div>
        <div>
            <h1 class="page-title" style="margin: 0 0 5px 0;">{{ $client->full_name }}</h1>
            <p style="color: var(--upwork-muted); margin: 0; direction: ltr; text-align: right;">{{ $client->phone_number }}</p>
        </div>
    </div>

    <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: var(--upwork-slate);">سجل الزيارات في مساحتك</h2>

    <div class="card">
        @if($visits->count() > 0)
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--upwork-border);">
                        <th style="padding: 12px;">تاريخ الدخول</th>
                        <th style="padding: 12px;">تاريخ الخروج</th>
                        <th style="padding: 12px;">المدة (بالدقائق)</th>
                        <th style="padding: 12px;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visits as $visit)
                        <tr style="border-bottom: 1px solid var(--upwork-border);">
                            <td style="padding: 12px;">{{ \Carbon\Carbon::parse($visit->check_in_at)->format('Y-m-d h:i A') }}</td>
                            <td style="padding: 12px;">
                                {{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->format('Y-m-d h:i A') : '--' }}
                            </td>
                            <td style="padding: 12px;">
                                {{ $visit->deducted_minutes ?? '--' }}
                            </td>
                            <td style="padding: 12px;">
                                @if($visit->status->value === 'CHECKED_IN')
                                    <span style="background: rgba(20, 168, 0, 0.1); color: #14a800; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">قيد التواجد</span>
                                @else
                                    <span style="background: #f2f2f2; color: #5e6d55; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">منصرف</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                {{ $visits->links() }}
            </div>
        @else
            <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">لم يقم العميل بأي زيارات بعد.</p>
        @endif
    </div>
</div>
@endsection
