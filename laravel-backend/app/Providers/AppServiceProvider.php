<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Attendance\Repositories\EloquentAttendanceRepository;
use App\Domain\Auth\Contracts\AuthRepositoryInterface;
use App\Domain\Auth\Repositories\EloquentAuthRepository;
use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Domain\Profile\Repositories\EloquentProfileRepository;
use App\Domain\Session\Contracts\SessionRepositoryInterface;
use App\Domain\Session\Repositories\EloquentSessionRepository;
use App\Domain\Subscription\Contracts\PlanRepositoryInterface;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Domain\Subscription\Repositories\EloquentPlanRepository;
use App\Domain\Subscription\Repositories\EloquentSubscriptionRepository;
use App\Domain\Workspace\Contracts\WorkspaceRepositoryInterface;
use App\Domain\Workspace\Repositories\EloquentWorkspaceRepository;
use App\Domain\WorkspacePortal\Contracts\WorkspacePortalRepositoryInterface;
use App\Domain\WorkspacePortal\Repositories\EloquentWorkspacePortalRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Repository interface => concrete implementation bindings (one per feature).
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        AuthRepositoryInterface::class => EloquentAuthRepository::class,
        WorkspaceRepositoryInterface::class => EloquentWorkspaceRepository::class,
        SessionRepositoryInterface::class => EloquentSessionRepository::class,
        AttendanceRepositoryInterface::class => EloquentAttendanceRepository::class,
        ProfileRepositoryInterface::class => EloquentProfileRepository::class,
        PlanRepositoryInterface::class => EloquentPlanRepository::class,
        SubscriptionRepositoryInterface::class => EloquentSubscriptionRepository::class,
        WorkspacePortalRepositoryInterface::class => EloquentWorkspacePortalRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Catch N+1 queries in dev; stay lenient in production.
        Model::preventLazyLoading(! $this->app->isProduction());

        // Throttle auth endpoints: 10 attempts/min per IP.
        RateLimiter::for('auth', fn (Request $request): Limit => Limit::perMinute(10)->by((string) $request->ip()));
        RateLimiter::for('public', fn (Request $request): Limit => Limit::perMinute(60)->by(
            (string) $request->ip().'|'.substr((string) $request->userAgent(), 0, 80)
        ));
        RateLimiter::for('api', fn (Request $request): Limit => Limit::perMinute(120)->by(
            (string) ($request->user()?->id ?? $request->ip())
        ));
    }
}
