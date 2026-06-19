@if($clients->count() > 0)
    <div style="overflow-x:auto;">
    <table style="width: 100%; min-width:1050px; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="border-bottom: 2px solid var(--upwork-border);">
                <th style="padding: 12px;">اسم الزائر</th>
                <th style="padding: 12px;">الباقة المستخدمة</th>
                <th style="padding: 12px;">رقم الهاتف</th>
                <th style="padding: 12px;">عدد الزيارات</th>
                <th style="padding: 12px;">مجموع الدقائق</th>
                <th style="padding: 12px;">الإيراد التقديري</th>
                <th style="padding: 12px;">آخر زيارة</th>
                <th style="padding: 12px;">التفاصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
                <tr style="border-bottom: 1px solid var(--upwork-border);">
                    <td style="padding: 12px; font-weight: bold;">{{ $client->full_name }}</td>
                    <td style="padding: 12px; white-space:nowrap;">
                        @foreach(explode(',', $client->plan_tiers) as $planTier)
                            @php
                                $planTier = trim($planTier);
                                $planDisplay = match ($planTier) {
                                    'WORKSPACE_SUBSCRIPTION' => ['اشتراك مساحة', '#8a6413', '#fff4cc'],
                                    'GLOBAL_SUBSCRIPTION' => ['عالمي', '#465363', '#e8edf3'],
                                    default => ['مجاني', 'var(--upwork-green-dark)', 'var(--upwork-green-soft)'],
                                };
                            @endphp
                            <span style="display:inline-block; background:{{ $planDisplay[2] }}; color:{{ $planDisplay[1] }}; padding:5px 10px; border-radius:20px; font-weight:800; font-size:12px;">
                                {{ $planDisplay[0] }}
                            </span>
                        @endforeach
                    </td>
                    <td style="padding: 12px; direction: ltr; text-align: right;">{{ $client->phone_number }}</td>
                    <td style="padding: 12px;">
                        <span style="background: var(--upwork-green-soft); color: var(--upwork-green-dark); padding: 4px 8px; border-radius: 20px; font-weight: bold; font-size: 13px;">
                            {{ $client->total_visits }}
                        </span>
                    </td>
                    <td style="padding: 12px; font-weight: bold;">
                        {{ number_format($client->total_minutes) }} دقيقة
                    </td>
                    <td style="padding: 12px; font-weight: bold; color: var(--upwork-green-dark);">
                        {{ number_format(($client->total_minutes / 60) * $workspace->effectiveHourlyRateEgp(), 2) }} ج.م
                    </td>
                    <td style="padding: 12px;">
                        {{ \Carbon\Carbon::parse($client->last_visit)->locale('ar')->diffForHumans() }}<br>
                        <small style="color: var(--upwork-muted); direction:ltr; display:inline-block;">{{ \Carbon\Carbon::parse($client->last_visit)->format('Y-m-d H:i') }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <a href="{{ route('workspace.clients.show', ['client' => $client->source_id, 'type' => $client->client_route_type]) }}" style="color: var(--upwork-blue); text-decoration: none;">عرض السجل</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:var(--upwork-green-soft); border-top:2px solid var(--upwork-green);">
                <td colspan="3" style="padding:14px 12px; font-weight:900;">إجمالي النتائج: {{ number_format($summary['total_visitors']) }} زائر</td>
                <td style="padding:14px 12px; font-weight:900;">{{ number_format($summary['total_visits']) }} زيارة</td>
                <td style="padding:14px 12px; font-weight:900;">{{ number_format($summary['total_minutes']) }} دقيقة</td>
                <td style="padding:14px 12px; font-weight:900; color:var(--upwork-green-dark);">{{ number_format($summary['total_revenue'], 2) }} ج.م</td>
                <td colspan="2" style="padding:14px 12px; color:var(--upwork-muted); font-size:12px;">{{ number_format($workspace->baseHourlyRateEgp(), 2) }} ج.م × معامل {{ number_format($workspace->hour_multiplier, 2) }} = {{ number_format($workspace->effectiveHourlyRateEgp(), 2) }} ج.م/ساعة</td>
            </tr>
        </tfoot>
    </table>
    </div>
    
    <div class="pagination-links">
        {{ $clients->links('vendor.pagination.upwork') }}
    </div>
@else
    <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">لا يوجد زوار حالياً يطابقون بحثك.</p>
@endif
