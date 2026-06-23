@if($clients->count() > 0)
    <div style="overflow-x:auto;">
    <table style="width: 100%; min-width:1050px; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="border-bottom: 2px solid var(--upwork-border);">
                <th style="padding: 12px;">{{ __('portal.clients.table.th_name') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_plan') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_phone') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_visits') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_minutes') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_revenue') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_last_visit') }}</th>
                <th style="padding: 12px;">{{ __('portal.clients.table.th_details') }}</th>
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
                                    'WORKSPACE_SUBSCRIPTION' => [__('portal.billing.WORKSPACE_SUBSCRIPTION'), '#8a6413', '#fff4cc'],
                                    'GLOBAL_SUBSCRIPTION' => [__('portal.billing.GLOBAL_SUBSCRIPTION'), '#465363', '#e8edf3'],
                                    default => [__('portal.billing.FREE'), 'var(--upwork-green-dark)', 'var(--upwork-green-soft)'],
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
                        {{ __('portal.clients.minutes', ['count' => number_format($client->total_minutes)]) }}
                    </td>
                    <td style="padding: 12px; font-weight: bold; color: var(--upwork-green-dark);">
                        {{ number_format(($client->total_minutes / 60) * $workspace->effectiveHourlyRateEgp(), 2) }} {{ __('portal.egp') }}
                    </td>
                    <td style="padding: 12px;">
                        {{ \Carbon\Carbon::parse($client->last_visit)->locale(app()->getLocale())->diffForHumans() }}<br>
                        <small style="color: var(--upwork-muted); direction:ltr; display:inline-block;">{{ \Carbon\Carbon::parse($client->last_visit)->format('Y-m-d H:i') }}</small>
                    </td>
                    <td style="padding: 12px;">
                        <a href="{{ route('workspace.clients.show', ['client' => $client->source_id, 'type' => $client->client_route_type]) }}" style="color: var(--upwork-blue); text-decoration: none;">{{ __('portal.clients.view_history') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background:var(--upwork-green-soft); border-top:2px solid var(--upwork-green);">
                <td colspan="3" style="padding:14px 12px; font-weight:900;">{{ __('portal.clients.table.footer_visitors', ['count' => number_format($summary['total_visitors'])]) }}</td>
                <td style="padding:14px 12px; font-weight:900;">{{ __('portal.clients.table.footer_visits', ['count' => number_format($summary['total_visits'])]) }}</td>
                <td style="padding:14px 12px; font-weight:900;">{{ __('portal.clients.minutes', ['count' => number_format($summary['total_minutes'])]) }}</td>
                <td style="padding:14px 12px; font-weight:900; color:var(--upwork-green-dark);">{{ number_format($summary['total_revenue'], 2) }} {{ __('portal.egp') }}</td>
                <td colspan="2" style="padding:14px 12px; color:var(--upwork-muted); font-size:12px;">{{ __('portal.clients.table.rate_formula', ['base' => number_format($workspace->baseHourlyRateEgp(), 2), 'mult' => number_format($workspace->hour_multiplier, 2), 'eff' => number_format($workspace->effectiveHourlyRateEgp(), 2)]) }}</td>
            </tr>
        </tfoot>
    </table>
    </div>
    
    <div class="pagination-links">
        {{ $clients->links('vendor.pagination.upwork') }}
    </div>
@else
    <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">{{ __('portal.clients.table.empty') }}</p>
@endif
