<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Attendance\Actions\OwnerCheckOutVisitAction;
use App\Domain\Attendance\Actions\OwnerRegisterVisitAction;
use App\Enums\VisitStatus;
use App\Exceptions\OwnerVisitException;
use App\Exceptions\OwnerVisitVerificationRequired;
use App\Http\Controllers\Controller;
use App\Models\WorkspaceVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WorkspaceVisitController extends Controller
{
    public function index(Request $request): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'plan' => ['nullable', Rule::in(['FREE', 'GLOBAL_SUBSCRIPTION', 'WORKSPACE_SUBSCRIPTION'])],
        ]);
        $now = now();
        $startOfDay = $now->copy()->startOfDay();
        $startOfWeek = $now->copy()->subDays(6)->startOfDay();   // last 7 days rolling
        $monthStart = $now->copy()->startOfMonth();

        // ── Active check-ins ─────────────────────────────────────────────────
        // Visitors who asked to leave float to the top (oldest request first, FIFO),
        // so the owner notices and approves them quickly.
        $activeVisits = WorkspaceVisit::with(['user', 'walkIn'])
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_IN->value)
            ->orderByRaw('checkout_requested_at IS NULL')   // pending requests first
            ->orderBy('checkout_requested_at')               // oldest request first
            ->latest('check_in_at')
            ->get();

        $pendingCheckoutCount = $activeVisits
            ->filter(fn (WorkspaceVisit $v) => $v->checkout_requested_at !== null)
            ->count();

        // ── Recent completed visits ───────────────────────────────────────────
        $recentVisitsQuery = WorkspaceVisit::with(['user', 'walkIn'])
            ->where('workspace_id', $workspace->id)
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->when($workspace->recent_visits_cleared_at, fn ($query) => $query->where('check_out_at', '>', $workspace->recent_visits_cleared_at))
            ->when(isset($filters['from']), fn ($query) => $query->where('check_out_at', '>=', Carbon::parse($filters['from'])))
            ->when(isset($filters['to']), fn ($query) => $query->where('check_out_at', '<', Carbon::parse($filters['to'])->addSecond()))
            ->when(isset($filters['plan']), function ($query) use ($filters) {
                if ($filters['plan'] === 'FREE') {
                    $query->where(function($q) {
                        $q->where('billing_source', 'FREE')->orWhereNull('billing_source');
                    });
                } else {
                    $query->where('billing_source', $filters['plan']);
                }
            })
            ->when(filled($filters['search'] ?? null), function ($query) use ($filters): void {
                $search = trim((string) $filters['search']);
                $query->where(function ($query) use ($search): void {
                    $query->whereHas('user', fn ($user) => $user
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%"))
                        ->orWhereHas('walkIn', fn ($walkIn) => $walkIn
                            ->where('full_name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%"));
                });
            });
        // Revenue is summed over per-visit CAPPED minutes so no visit bills beyond
        // the workspace's daily-hours ceiling (ساعات احتساب اليوم).
        $capMinutes = $workspace->dailyCapMinutes();
        $recentSummaryRow = (clone $recentVisitsQuery)
            ->toBase()
            ->selectRaw('COUNT(*) as total_visits')
            ->selectRaw('COALESCE(SUM(duration_minutes), 0) as total_minutes')
            ->selectRaw('COALESCE(SUM(LEAST(duration_minutes, ?)), 0) as total_billable_minutes', [$capMinutes])
            ->first();
        $recentSummary = [
            'total_visits' => (int) ($recentSummaryRow->total_visits ?? 0),
            'total_minutes' => (int) ($recentSummaryRow->total_minutes ?? 0),
            'total_visitors' => (clone $recentVisitsQuery)->whereNotNull('user_id')->distinct()->count('user_id')
                + (clone $recentVisitsQuery)->whereNotNull('walk_in_id')->distinct()->count('walk_in_id'),
        ];
        $recentSummary['total_revenue'] = round(((int) ($recentSummaryRow->total_billable_minutes ?? 0) / 60) * $workspace->effectiveHourlyRateEgp(), 2);
        $recentVisits = $recentVisitsQuery->latest('check_out_at')->paginate(20)->withQueryString();

        // ── Room reservations folded into this table + its totals ─────────────
        // Rooms have their own price, so their cost is ADDED on top of the
        // visit-hours revenue (not re-billed at the workspace rate). Hidden when
        // the owner filters by a billing plan, since rooms aren't a billing source.
        $roomReservations = collect();
        if (! isset($filters['plan'])) {
            $roomReservations = \App\Models\RoomReservation::with('room')
                ->where('workspace_id', $workspace->id)
                ->where('status', \App\Enums\RoomReservationStatus::RESERVED->value)
                ->when($workspace->recent_visits_cleared_at, fn ($q) => $q->where('created_at', '>', $workspace->recent_visits_cleared_at))
                ->when(isset($filters['from']), fn ($q) => $q->where('starts_at', '>=', Carbon::parse($filters['from'])))
                ->when(isset($filters['to']), fn ($q) => $q->where('starts_at', '<', Carbon::parse($filters['to'])->addSecond()))
                ->when(filled($filters['search'] ?? null), function ($q) use ($filters): void {
                    $search = trim((string) $filters['search']);
                    $q->where(fn ($qq) => $qq->where('client_name', 'like', "%{$search}%")->orWhere('client_phone', 'like', "%{$search}%"));
                })
                ->latest('created_at')
                ->get();

            $roomMinutes = (int) $roomReservations->sum(fn ($r) => $r->durationMinutes());
            $roomRevenue = round($roomReservations->sum('total_cost_cents') / 100, 2);

            $recentSummary['total_revenue'] = round($recentSummary['total_revenue'] + $roomRevenue, 2);
            $recentSummary['total_minutes'] += $roomMinutes;
            $recentSummary['total_visits'] += $roomReservations->count();
            $recentSummary['total_visitors'] += $roomReservations->pluck('client_phone')->unique()->count();
        }

        // ── Base query helper ─────────────────────────────────────────────────
        $base = fn () => WorkspaceVisit::where('workspace_id', $workspace->id);

        // ── Completed hours in each period ───────────────────────────────────
        $completedMinutesToday = (int) $base()
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $startOfDay)
            ->where('check_out_at', '<', $startOfDay->copy()->addDay())
            ->sum('duration_minutes');

        // Add live elapsed minutes of currently active visits so "today hours"
        // reflects the real running total, not just closed visits.
        $liveMinutesToday = $activeVisits->sum(
            fn (WorkspaceVisit $v) => (int) abs($v->check_in_at->diffInMinutes($now))
        );

        $completedMinutesWeek = (int) $base()
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $startOfWeek)
            ->where('check_out_at', '<', $now->copy()->addSecond())
            ->sum('duration_minutes');

        $completedMinutesMonth = (int) $base()
            ->where('status', VisitStatus::CHECKED_OUT->value)
            ->where('check_out_at', '>=', $monthStart)
            ->where('check_out_at', '<', $now->copy()->addSecond())
            ->sum('duration_minutes');

        // ── Visitor counts ────────────────────────────────────────────────────
        $visitorsToday = $base()
            ->where('check_in_at', '>=', $startOfDay)
            ->where('check_in_at', '<', $startOfDay->copy()->addDay())
            ->count();

        $visitorsWeek = $base()
            ->where('check_in_at', '>=', $startOfWeek)
            ->count();

        $visitorsMonth = $base()
            ->where('check_in_at', '>=', $monthStart)
            ->count();

        $stats = [
            'active' => $activeVisits->count(),

            // Visitor counts
            'today_visitors' => $visitorsToday,
            'week_visitors' => $visitorsWeek,
            'month_visitors' => $visitorsMonth,

            // Hour totals (rounded to 1 decimal)
            'today_hours' => round(($completedMinutesToday + $liveMinutesToday) / 60, 1),
            'week_hours' => round($completedMinutesWeek / 60, 1),
            'month_hours' => round($completedMinutesMonth / 60, 1),
        ];

        // Lightweight polling: the front-end re-fetches just the active table.
        if ($request->ajax() && $request->query('partial') === 'active') {
            return view('workspace.visits.partials.active-table', compact('workspace', 'activeVisits', 'pendingCheckoutCount'));
        }

        return view('workspace.visits.index', compact('workspace', 'activeVisits', 'recentVisits', 'recentSummary', 'stats', 'pendingCheckoutCount', 'roomReservations'));
    }

    public function clearRecent(): RedirectResponse
    {
        Auth::user()->ownedWorkspace->update(['recent_visits_cleared_at' => now()]);

        return redirect()->route('workspace.visits.index')->with('success', 'تم مسح قائمة أحدث الزيارات من العرض فقط. لم يتم حذف أي سجلات.');
    }

    public function store(Request $request, OwnerRegisterVisitAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:30'],
            'name' => ['nullable', 'string', 'max:120'],
            'visitor_password' => ['nullable', 'string', 'max:72'],
        ]);

        try {
            $action->handle(
                Auth::user()->ownedWorkspace,
                $validated['phone_number'],
                $validated['name'] ?? null,
                (string) Auth::id(),
                $validated['visitor_password'] ?? null,
            );
        } catch (OwnerVisitVerificationRequired $e) {
            return back()
                ->with('paid_visitor_verification', [
                    'phone_number' => $e->phoneNumber,
                    'visitor_name' => $e->visitorName,
                    'billing_source' => $e->billingSource->value,
                ])
                ->withInput($request->only('phone_number', 'name'));
        } catch (OwnerVisitException $e) {
            return back()
                ->withErrors(['phone_number' => $e->getMessage()])
                ->withInput($request->only('phone_number', 'name'));
        }

        return back()->with('success', 'تم تسجيل دخول الزائر بنجاح.');
    }

    public function checkOut(WorkspaceVisit $visit, OwnerCheckOutVisitAction $action): RedirectResponse
    {
        try {
            $closedVisit = $action->handle(Auth::user()->ownedWorkspace, $visit->id);
        } catch (OwnerVisitException $e) {
            return back()->withErrors(['checkout' => $e->getMessage()]);
        }

        $workspace = Auth::user()->ownedWorkspace;
        $mins = $closedVisit->duration_minutes ?? 0;
        $hours = intdiv($mins, 60);
        $remainingMins = $mins % 60;
        $price = $workspace->estimatedRevenueEgp((int) $mins);

        return back()->with('success', 'تم تسجيل خروج الزائر بنجاح.')
                     ->with('checkout_summary', [
            'visitor_name' => $closedVisit->visitor_name,
            'duration_minutes' => $mins,
            'duration_label' => $hours > 0 ? "{$hours} ساعة و {$remainingMins} دقيقة" : "{$remainingMins} دقيقة",
            'price' => number_format($price, 2),
            'billing_source' => $closedVisit->billing_source?->value ?? 'FREE',
            'check_in_at' => $closedVisit->check_in_at?->format('h:i A'),
            'check_out_at' => $closedVisit->check_out_at?->format('h:i A'),
        ]);
    }
}
