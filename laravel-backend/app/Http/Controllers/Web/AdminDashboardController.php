<?php

namespace App\Http\Controllers\Web;

use App\Enums\SubscriptionStatus;
use App\Enums\VisitStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceSettlement;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            $startOfDay = now()->startOfDay();

            $monthStart = now()->startOfMonth();

            return [
                'active_workspaces' => Workspace::where('is_active', true)->count(),
                'active_subscriptions' => Subscription::where('status', SubscriptionStatus::ACTIVE)->count(),
                'paid_periods_this_month' => WorkspaceSettlement::where('paid_at', '>=', $monthStart)->count(),
                'todays_visits' => WorkspaceVisit::where('check_in_at', '>=', $startOfDay)
                    ->where('check_in_at', '<', $startOfDay->copy()->addDay())
                    ->count(),
                'active_visits' => WorkspaceVisit::where('status', VisitStatus::CHECKED_IN)->count(),
                'total_users' => User::count(),
                'walk_in_users' => WorkspaceWalkIn::count(),
                'hours_this_month' => round(((int) WorkspaceVisit::where('status', VisitStatus::CHECKED_OUT)
                    ->where('check_out_at', '>=', $monthStart)
                    ->sum('duration_minutes')) / 60, 1),
                'silver_subscriptions' => Subscription::where('status', SubscriptionStatus::ACTIVE)
                    ->whereHas('plan', fn ($q) => $q->where('tier', 'SILVER'))->count(),
                'gold_subscriptions' => Subscription::where('status', SubscriptionStatus::ACTIVE)
                    ->whereHas('plan', fn ($q) => $q->where('tier', 'GOLD'))->count(),
            ];
        });

        return view('admin.dashboard', compact('stats'));
    }
}
