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
use App\Domain\WorkspaceSubscription\Contracts\WorkspacePlanRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionLedgerRepositoryInterface;
use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionRepositoryInterface;
use App\Domain\WorkspaceSubscription\Repositories\EloquentWorkspacePlanRepository;
use App\Domain\WorkspaceSubscription\Repositories\EloquentWorkspaceSubscriptionCodeRepository;
use App\Domain\WorkspaceSubscription\Repositories\EloquentWorkspaceSubscriptionLedgerRepository;
use App\Domain\WorkspaceSubscription\Repositories\EloquentWorkspaceSubscriptionRepository;
use App\Domain\Room\Contracts\RoomClientRepositoryInterface;
use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Domain\Room\Contracts\RoomReservationRepositoryInterface;
use App\Domain\Room\Repositories\EloquentRoomClientRepository;
use App\Domain\Room\Repositories\EloquentRoomRepository;
use App\Domain\Room\Repositories\EloquentRoomReservationRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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
        WorkspacePlanRepositoryInterface::class => EloquentWorkspacePlanRepository::class,
        WorkspaceSubscriptionRepositoryInterface::class => EloquentWorkspaceSubscriptionRepository::class,
        WorkspaceSubscriptionCodeRepositoryInterface::class => EloquentWorkspaceSubscriptionCodeRepository::class,
        WorkspaceSubscriptionLedgerRepositoryInterface::class => EloquentWorkspaceSubscriptionLedgerRepository::class,
        RoomRepositoryInterface::class => EloquentRoomRepository::class,
        RoomClientRepositoryInterface::class => EloquentRoomClientRepository::class,
        RoomReservationRepositoryInterface::class => EloquentRoomReservationRepository::class,
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
        RateLimiter::for('activation', fn (Request $request): Limit => Limit::perMinute(10)->by(
            (string) ($request->user()?->id ?? $request->ip())
        ));

        DB::listen(function (QueryExecuted $query): void {
            if ($query->time >= (float) env('SLOW_QUERY_MS', 500)) {
                Log::warning('database.slow_query', [
                    'connection' => $query->connectionName,
                    'time_ms' => $query->time,
                    'sql' => $query->toRawSql(),
                ]);
            }
        });
        Queue::failing(fn (JobFailed $event) => Log::error('queue.job_failed', [
            'connection' => $event->connectionName,
            'queue' => $event->job->getQueue(),
            'job' => $event->job->resolveName(),
            'error' => $event->exception->getMessage(),
        ]));
    }
}
