<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\RoomReservationStatus;
use App\Enums\SessionStatus;
use App\Enums\VisitStatus;
use App\Enums\WorkspaceSubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\NotificationCampaign;
use App\Models\RoomReservation;
use App\Models\StudySession;
use App\Models\WorkspaceCenterSubject;
use App\Models\WorkspaceCenterTeacher;
use App\Models\WorkspaceClient;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Models\WorkspaceRoom;
use App\Models\WorkspaceSubscription;
use App\Models\WorkspaceVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class WorkspaceAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $filters = $this->resolveFilters($request);
        $from = $filters['from'];
        $to = $filters['to'];

        $visitsInRange = WorkspaceVisit::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('check_in_at', [$from, $to]);

        $completedVisitsInRange = WorkspaceVisit::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->whereBetween('check_out_at', [$from, $to]);

        $capMinutes = $workspace->dailyCapMinutes();
        $billableMinutesExpression = DB::connection()->getDriverName() === 'sqlite'
            ? 'MIN(duration_minutes, ?)'
            : 'LEAST(duration_minutes, ?)';
        $visitTotals = (clone $completedVisitsInRange)
            ->toBase()
            ->selectRaw('COUNT(*) as completed_visits')
            ->selectRaw('COALESCE(SUM(duration_minutes), 0) as total_minutes')
            ->selectRaw("COALESCE(SUM({$billableMinutesExpression}), 0) as billable_minutes", [$capMinutes])
            ->first();

        $billableMinutes = (int) ($visitTotals->billable_minutes ?? 0);
        $visitRevenueEgp = round(($billableMinutes / 60) * $workspace->effectiveHourlyRateEgp(), 2);

        $roomReservationsInRange = RoomReservation::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('starts_at', [$from, $to]);

        $roomTotals = (clone $roomReservationsInRange)
            ->toBase()
            ->selectRaw('COUNT(*) as reservations')
            ->selectRaw("SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as active_reservations", [RoomReservationStatus::RESERVED->value])
            ->selectRaw('COALESCE(SUM(CASE WHEN status = ? THEN total_cost_cents ELSE 0 END), 0) as revenue_cents', [RoomReservationStatus::RESERVED->value])
            ->first();

        $privateSessionsInRange = WorkspacePrivateSession::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('starts_at', [$from, $to]);

        $privateSessionTotals = (clone $privateSessionsInRange)
            ->toBase()
            ->selectRaw('COUNT(*) as sessions')
            ->selectRaw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_sessions")
            ->selectRaw("SUM(CASE WHEN status = 'finished' THEN 1 ELSE 0 END) as finished_sessions")
            ->selectRaw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_sessions")
            ->first();

        $privateSessionAttendees = WorkspacePrivateSessionAttendee::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('created_at', [$from, $to]);

        $privateSessionAttendance = WorkspacePrivateSessionAttendee::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', 'attended')
            ->whereBetween('checked_in_at', [$from, $to]);

        $privateSessionAttendanceTotals = (clone $privateSessionAttendance)
            ->toBase()
            ->selectRaw('COUNT(*) as attended')
            ->selectRaw('COALESCE(SUM(amount_cents), 0) as revenue_cents')
            ->selectRaw("SUM(CASE WHEN checked_in_method = 'qr' THEN 1 ELSE 0 END) as qr_checkins")
            ->selectRaw("SUM(CASE WHEN checked_in_method = 'owner' THEN 1 ELSE 0 END) as owner_checkins")
            ->first();

        $subscriptionsInRange = WorkspaceSubscription::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('started_at', [$from, $to]);

        $subscriptionTotals = (clone $subscriptionsInRange)
            ->toBase()
            ->selectRaw('COUNT(*) as issued')
            ->selectRaw('COALESCE(SUM(price_cents_snapshot), 0) as revenue_cents')
            ->first();

        $activeSubscriptions = WorkspaceSubscription::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->count();

        $lowHourSubscriptions = WorkspaceSubscription::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->where('remaining_minutes', '<', 16 * 60)
            ->count();

        $expiringSubscriptions = WorkspaceSubscription::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', WorkspaceSubscriptionStatus::ACTIVE->value)
            ->whereBetween('expires_at', [now(), now()->addDays(2)])
            ->count();

        $notificationTotals = NotificationCampaign::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('created_at', [$from, $to])
            ->toBase()
            ->selectRaw('COUNT(*) as campaigns')
            ->selectRaw('COALESCE(SUM(targeted_count), 0) as targeted')
            ->selectRaw('COALESCE(SUM(sent_count), 0) as sent')
            ->selectRaw('COALESCE(SUM(failed_count), 0) as failed')
            ->selectRaw('COALESCE(SUM(opened_count), 0) as opened')
            ->selectRaw('COALESCE(SUM(clicked_count), 0) as clicked')
            ->first();

        $publicSessions = StudySession::query()
            ->where('workspace_id', $workspace->id)
            ->whereBetween('start_time', [$from, $to]);

        $publicSessionTotals = (clone $publicSessions)
            ->toBase()
            ->selectRaw('COUNT(*) as sessions')
            ->selectRaw('COALESCE(SUM(price_cents), 0) as listed_revenue_cents')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as upcoming', [SessionStatus::UPCOMING->value])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as ended', [SessionStatus::ENDED->value])
            ->first();

        $totalRevenueEgp = $visitRevenueEgp
            + ((int) ($roomTotals->revenue_cents ?? 0) / 100)
            + ((int) ($privateSessionAttendanceTotals->revenue_cents ?? 0) / 100)
            + ((int) ($subscriptionTotals->revenue_cents ?? 0) / 100);

        $activeNow = WorkspaceVisit::query()
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->count();

        $uniqueVisitVisitors = (clone $visitsInRange)->whereNotNull('user_id')->distinct()->count('user_id')
            + (clone $visitsInRange)->whereNotNull('walk_in_id')->distinct()->count('walk_in_id');

        $summary = [
            'total_revenue_egp' => round($totalRevenueEgp, 2),
            'visit_revenue_egp' => $visitRevenueEgp,
            'room_revenue_egp' => round(((int) ($roomTotals->revenue_cents ?? 0)) / 100, 2),
            'private_session_revenue_egp' => round(((int) ($privateSessionAttendanceTotals->revenue_cents ?? 0)) / 100, 2),
            'subscription_revenue_egp' => round(((int) ($subscriptionTotals->revenue_cents ?? 0)) / 100, 2),
            'visits' => (clone $visitsInRange)->count(),
            'completed_visits' => (int) ($visitTotals->completed_visits ?? 0),
            'unique_visitors' => $uniqueVisitVisitors,
            'active_now' => $activeNow,
            'visit_hours' => round(((int) ($visitTotals->total_minutes ?? 0)) / 60, 1),
            'billable_hours' => round($billableMinutes / 60, 1),
        ];

        $operations = [
            'rooms_count' => WorkspaceRoom::query()->where('workspace_id', $workspace->id)->count(),
            'active_rooms_count' => WorkspaceRoom::query()->where('workspace_id', $workspace->id)->where('is_active', true)->count(),
            'room_reservations' => (int) ($roomTotals->reservations ?? 0),
            'active_room_reservations' => (int) ($roomTotals->active_reservations ?? 0),
            'public_sessions' => (int) ($publicSessionTotals->sessions ?? 0),
            'public_upcoming_sessions' => (int) ($publicSessionTotals->upcoming ?? 0),
            'private_sessions' => (int) ($privateSessionTotals->sessions ?? 0),
            'private_active_sessions' => (int) ($privateSessionTotals->active_sessions ?? 0),
            'private_finished_sessions' => (int) ($privateSessionTotals->finished_sessions ?? 0),
            'private_cancelled_sessions' => (int) ($privateSessionTotals->cancelled_sessions ?? 0),
            'private_invited' => (clone $privateSessionAttendees)->count(),
            'private_attended' => (int) ($privateSessionAttendanceTotals->attended ?? 0),
            'private_qr_checkins' => (int) ($privateSessionAttendanceTotals->qr_checkins ?? 0),
            'private_owner_checkins' => (int) ($privateSessionAttendanceTotals->owner_checkins ?? 0),
            'subscriptions_issued' => (int) ($subscriptionTotals->issued ?? 0),
            'active_subscriptions' => $activeSubscriptions,
            'low_hour_subscriptions' => $lowHourSubscriptions,
            'expiring_subscriptions' => $expiringSubscriptions,
            'notification_campaigns' => (int) ($notificationTotals->campaigns ?? 0),
            'notifications_sent' => (int) ($notificationTotals->sent ?? 0),
            'notifications_failed' => (int) ($notificationTotals->failed ?? 0),
            'notification_open_rate' => $this->rate((int) ($notificationTotals->opened ?? 0), (int) ($notificationTotals->sent ?? 0)),
            'notification_click_rate' => $this->rate((int) ($notificationTotals->clicked ?? 0), (int) ($notificationTotals->sent ?? 0)),
        ];

        return view('workspace.analytics.index', [
            'workspace' => $workspace,
            'filters' => $filters,
            'summary' => $summary,
            'operations' => $operations,
            'trend' => $this->visitTrend($workspace->id, $from, $to),
            'topClients' => $this->topClients($workspace->id),
            'topRooms' => $this->topRooms($workspace->id, $from, $to),
            'topPrivateSessions' => $this->topPrivateSessions($workspace->id, $from, $to),
            'educationBreakdown' => $this->educationBreakdown($workspace->id, $from, $to),
        ]);
    }

    /** @return array{period:string, from:Carbon, to:Carbon} */
    private function resolveFilters(Request $request): array
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:today,7_days,30_days,month,custom'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $period = (string) ($validated['period'] ?? '30_days');
        $now = now();

        return match ($period) {
            'today' => [
                'period' => $period,
                'from' => $now->copy()->startOfDay(),
                'to' => $now->copy()->endOfDay(),
            ],
            '7_days' => [
                'period' => $period,
                'from' => $now->copy()->subDays(6)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
            ],
            'month' => [
                'period' => $period,
                'from' => $now->copy()->startOfMonth(),
                'to' => $now->copy()->endOfMonth(),
            ],
            'custom' => [
                'period' => $period,
                'from' => isset($validated['from']) ? Carbon::parse($validated['from'])->startOfDay() : $now->copy()->subDays(29)->startOfDay(),
                'to' => isset($validated['to']) ? Carbon::parse($validated['to'])->endOfDay() : $now->copy()->endOfDay(),
            ],
            default => [
                'period' => '30_days',
                'from' => $now->copy()->subDays(29)->startOfDay(),
                'to' => $now->copy()->endOfDay(),
            ],
        };
    }

    /** @return array<int, array{label:string, visits:int, revenue:float, percent:int}> */
    private function visitTrend(string $workspaceId, Carbon $from, Carbon $to): array
    {
        $days = [];
        for ($day = $from->copy()->startOfDay(); $day->lte($to); $day->addDay()) {
            $key = $day->toDateString();
            $days[$key] = [
                'label' => $day->format('m/d'),
                'visits' => 0,
                'revenue' => 0.0,
                'percent' => 0,
            ];
        }

        $rows = WorkspaceVisit::query()
            ->where('workspace_id', $workspaceId)
            ->whereBetween('check_in_at', [$from, $to])
            ->selectRaw('DATE(check_in_at) as visit_day')
            ->selectRaw('COUNT(*) as visits')
            ->groupBy('visit_day')
            ->orderBy('visit_day')
            ->get();

        foreach ($rows as $row) {
            $key = (string) $row->visit_day;
            if (isset($days[$key])) {
                $days[$key]['visits'] = (int) $row->visits;
            }
        }

        $max = max(1, max(array_column($days, 'visits')));

        return array_map(static function (array $day) use ($max): array {
            $day['percent'] = (int) round(($day['visits'] / $max) * 100);

            return $day;
        }, array_values($days));
    }

    private function topClients(string $workspaceId)
    {
        return WorkspaceClient::query()
            ->where('workspace_id', $workspaceId)
            ->orderByDesc('total_visits')
            ->orderByDesc('total_minutes')
            ->limit(8)
            ->get();
    }

    private function topRooms(string $workspaceId, Carbon $from, Carbon $to)
    {
        return RoomReservation::query()
            ->select('room_id')
            ->selectRaw('COUNT(*) as reservations_count')
            ->selectRaw('COALESCE(SUM(total_cost_cents), 0) as revenue_cents')
            ->with('room')
            ->where('workspace_id', $workspaceId)
            ->where('status', RoomReservationStatus::RESERVED->value)
            ->whereBetween('starts_at', [$from, $to])
            ->groupBy('room_id')
            ->orderByDesc('revenue_cents')
            ->limit(6)
            ->get();
    }

    private function topPrivateSessions(string $workspaceId, Carbon $from, Carbon $to)
    {
        return WorkspacePrivateSession::query()
            ->withCount([
                'attendees as attended_count' => fn ($query) => $query->where('status', 'attended'),
            ])
            ->withSum([
                'attendees as attended_revenue_cents' => fn ($query) => $query->where('status', 'attended'),
            ], 'amount_cents')
            ->where('workspace_id', $workspaceId)
            ->whereBetween('starts_at', [$from, $to])
            ->orderByDesc('attended_revenue_cents')
            ->limit(6)
            ->get();
    }

    /** @return array{teachers:mixed, subjects:mixed} */
    private function educationBreakdown(string $workspaceId, Carbon $from, Carbon $to): array
    {
        $teacherIds = WorkspacePrivateSession::query()
            ->where('workspace_id', $workspaceId)
            ->whereBetween('starts_at', [$from, $to])
            ->whereNotNull('center_teacher_id')
            ->select('center_teacher_id')
            ->selectRaw('COUNT(*) as sessions_count')
            ->groupBy('center_teacher_id')
            ->orderByDesc('sessions_count')
            ->limit(6)
            ->pluck('sessions_count', 'center_teacher_id');

        $subjectIds = WorkspacePrivateSession::query()
            ->where('workspace_id', $workspaceId)
            ->whereBetween('starts_at', [$from, $to])
            ->whereNotNull('center_subject_id')
            ->select('center_subject_id')
            ->selectRaw('COUNT(*) as sessions_count')
            ->groupBy('center_subject_id')
            ->orderByDesc('sessions_count')
            ->limit(6)
            ->pluck('sessions_count', 'center_subject_id');

        return [
            'teachers' => WorkspaceCenterTeacher::query()
                ->whereIn('id', $teacherIds->keys())
                ->get()
                ->map(fn ($teacher) => [
                    'name' => $teacher->name,
                    'count' => (int) $teacherIds[$teacher->id],
                ])
                ->sortByDesc('count')
                ->values(),
            'subjects' => WorkspaceCenterSubject::query()
                ->whereIn('id', $subjectIds->keys())
                ->get()
                ->map(fn ($subject) => [
                    'name' => $subject->name,
                    'count' => (int) $subjectIds[$subject->id],
                ])
                ->sortByDesc('count')
                ->values(),
        ];
    }

    private function rate(int $part, int $whole): float
    {
        if ($whole <= 0) {
            return 0.0;
        }

        return round(($part / $whole) * 100, 1);
    }
}
