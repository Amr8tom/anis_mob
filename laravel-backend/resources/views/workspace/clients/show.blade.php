@extends('workspace.layouts.app')

@section('content')
<div class="settings-container">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('workspace.clients.index') }}" style="color: var(--upwork-green); text-decoration: none; font-weight: bold;">{{ __('portal.clients.show.back') }}</a>
    </div>

    <div class="card" style="margin-bottom: 20px; display: flex; align-items: center; gap: 20px;">
        <div style="width: 80px; height: 80px; background: var(--upwork-green-soft); color: var(--upwork-green-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold;">
            {{ mb_substr($client->full_name, 0, 1) }}
        </div>
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                <h1 class="page-title" style="margin: 0;">{{ $client->full_name }}</h1>
                @if($type === 'user')
                    <span style="background: var(--upwork-blue); color: white; padding: 4px 8px; border-radius: 20px; font-weight: bold; font-size: 12px;">{{ __('portal.clients.show.badge_app') }}</span>
                @else
                    <span style="background: var(--upwork-muted); color: white; padding: 4px 8px; border-radius: 20px; font-weight: bold; font-size: 12px;">{{ __('portal.clients.show.badge_walkin') }}</span>
                @endif
            </div>
            <p style="color: var(--upwork-muted); margin: 0; direction: ltr; text-align: right;">{{ $client->phone_number }}</p>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid-2" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); margin-bottom: 20px;">
        <div class="card" style="text-align: center; padding: 15px;">
            <div style="font-size: 24px; font-weight: 900; color: var(--upwork-green);">{{ $totalVisits }}</div>
            <div style="color: var(--upwork-muted); font-size: 13px; margin-top: 4px; font-weight: bold;">{{ __('portal.clients.show.total_visits') }}</div>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <div style="font-size: 24px; font-weight: 900; color: var(--upwork-blue);">{{ number_format($totalMinutes / 60, 2) }}</div>
            <div style="color: var(--upwork-muted); font-size: 13px; margin-top: 4px; font-weight: bold;">{{ __('portal.clients.show.total_hours') }}</div>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <div style="font-size: 24px; font-weight: 900; color: var(--upwork-slate);">{{ $totalVisits > 0 ? number_format(($totalMinutes / 60) / $totalVisits, 1) : 0 }}</div>
            <div style="color: var(--upwork-muted); font-size: 13px; margin-top: 4px; font-weight: bold;">{{ __('portal.clients.show.avg_hours') }}</div>
        </div>
        <div class="card" style="text-align: center; padding: 15px;">
            <div style="font-size: 16px; font-weight: 900; color: var(--upwork-slate); direction: ltr;">
                {{ $lastVisit ? \Carbon\Carbon::parse($lastVisit)->format('Y-m-d') : '--' }}
            </div>
            <div style="color: var(--upwork-muted); font-size: 13px; margin-top: 4px; font-weight: bold;">{{ __('portal.clients.show.last_visit') }}</div>
        </div>
    </div>

    <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px; color: var(--upwork-slate);">{{ __('portal.clients.show.history_title') }}</h2>

    <div class="card">
        @if($visits->count() > 0)
            <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--upwork-border);">
                        <th style="padding: 12px;">{{ __('portal.clients.show.th_check_in') }}</th>
                        <th style="padding: 12px;">{{ __('portal.clients.show.th_check_out') }}</th>
                        <th style="padding: 12px;">{{ __('portal.clients.show.th_duration') }}</th>
                        <th style="padding: 12px;">{{ __('portal.clients.show.th_status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visits as $visit)
                        <tr style="border-bottom: 1px solid var(--upwork-border);">
                            <td style="padding: 12px;">{{ \Carbon\Carbon::parse($visit->check_in_at)->format('Y-m-d h:i A') }}</td>
                            <td style="padding: 12px;">
                                {{ $visit->check_out_at ? \Carbon\Carbon::parse($visit->check_out_at)->format('Y-m-d h:i A') : '--' }}
                            </td>
                            <td style="padding: 12px; font-weight: bold;">
                                {{ $visit->duration_minutes ?? '--' }}
                            </td>
                            <td style="padding: 12px;">
                                @if($visit->status->value === 'CHECKED_IN')
                                    <span style="background: rgba(20, 168, 0, 0.1); color: #14a800; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">{{ __('portal.clients.show.status_checked_in') }}</span>
                                @else
                                    <span style="background: #f2f2f2; color: #5e6d55; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">{{ __('portal.clients.show.status_checked_out') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            {{ $visits->links('vendor.pagination.upwork') }}
        @else
            <p style="text-align: center; color: var(--upwork-muted); padding: 40px;">{{ __('portal.clients.show.empty') }}</p>
        @endif
    </div>
</div>
@endsection
