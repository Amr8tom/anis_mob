<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Contracts\AttendanceRepositoryInterface;
use App\Domain\Attendance\Data\VisitFunding;
use App\Domain\Subscription\Contracts\SubscriptionRepositoryInterface;
use App\Enums\BillingSource;
use App\Enums\PlanTier;
use App\Enums\VisitSource;
use App\Enums\VisitStatus;
use App\Exceptions\OwnerVisitException;
use App\Exceptions\OwnerVisitVerificationRequired;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceVisit;
use App\Models\WorkspaceWalkIn;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final readonly class OwnerRegisterVisitAction
{
    public function __construct(
        private AttendanceRepositoryInterface $visits,
        private ResolveVisitFundingAction $funding,
        private SubscriptionRepositoryInterface $subscriptions,
    ) {}

    public function handle(Workspace $workspace, string $phone, ?string $name, string $registeredBy, ?string $visitorPassword = null): WorkspaceVisit
    {
        return DB::transaction(function () use ($workspace, $phone, $name, $registeredBy, $visitorPassword): WorkspaceVisit {
            $phone = trim($phone);
            $normalizedPhone = User::normalizePhone($phone) ?? '';
            $user = User::where('phone_number_normalized', $normalizedPhone)->first();
            if ($user !== null) {
                return $this->registerAppUser($workspace, $user, $registeredBy, $visitorPassword);
            }

            $walkIn = WorkspaceWalkIn::withTrashed()
                ->where('workspace_id', $workspace->id)
                ->where('phone_number_normalized', $normalizedPhone)
                ->first();
            if ($walkIn === null) {
                $walkIn = WorkspaceWalkIn::create([
                    'workspace_id' => $workspace->id,
                    'phone_number' => $phone,
                    'phone_number_normalized' => $normalizedPhone,
                    'full_name' => filled($name) ? trim((string) $name) : 'زائر',
                ]);
            }
            if ($walkIn->trashed()) {
                $walkIn->restore();
            }

            if (WorkspaceVisit::where('walk_in_id', $walkIn->id)->where('status', VisitStatus::CHECKED_IN->value)->exists()) {
                throw new OwnerVisitException('هذا الزائر لديه زيارة نشطة بالفعل.');
            }
            if (! $this->visits->reserveOccupancy($workspace)) {
                throw new OwnerVisitException('مساحة العمل ممتلئة حالياً.');
            }

            // A walk-in may hold a workspace (special) subscription. Use it if usable,
            // otherwise the visit is FREE / direct-pay.
            $funding = $this->funding->handleForWalkIn($workspace, $walkIn->id, lockWorkspaceSubscription: true);

            try {
                return WorkspaceVisit::create([
                    'user_id' => null,
                    'walk_in_id' => $walkIn->id,
                    'workspace_id' => $workspace->id,
                    'subscription_id' => null,
                    'workspace_subscription_id' => $funding->workspaceSubscriptionId,
                    'billing_source' => $funding->billingSource->value,
                    'plan_tier_snapshot' => $funding->planTierSnapshot ?? PlanTier::FREE->value,
                    'status' => VisitStatus::CHECKED_IN->value,
                    'source' => VisitSource::OWNER->value,
                    'registered_by' => $registeredBy,
                    'check_in_at' => now(),
                    'active_flag' => 1,
                ])->load('walkIn');
            } catch (QueryException $exception) {
                if (($exception->errorInfo[1] ?? null) === 1062 || $exception->getCode() === '23000') {
                    throw new OwnerVisitException('هذا الزائر لديه زيارة نشطة بالفعل.');
                }
                throw $exception;
            }
        });
    }

    private function registerAppUser(Workspace $workspace, User $user, string $registeredBy, ?string $visitorPassword): WorkspaceVisit
    {
        if ($this->visits->activeVisitForUser($user->id) !== null) {
            throw new OwnerVisitException('هذا المستخدم لديه زيارة نشطة بالفعل.');
        }

        // Owner-created visits allow registered free/new users to pay the workspace
        // directly. Workspace subscriptions still take priority, followed by a paid
        // global app subscription; no app funding becomes a FREE/direct-pay visit.
        $funding = $this->funding->handle($workspace, $user->id, lockWorkspaceSubscription: true);
        if ($funding === null) {
            $global = $this->subscriptions->activeForUser($user->id);
            if ($global !== null && $global->plan->tier !== PlanTier::FREE) {
                throw new OwnerVisitException('لا يوجد رصيد كافٍ في اشتراك التطبيق لتسجيل دخول هذا المستخدم.');
            }

            $funding = VisitFunding::free();
        }

        // Only spending the user's global app subscription needs confirmation.
        // Direct-pay/free and this workspace's own subscription are owner-managed.
        if ($funding->billingSource === BillingSource::GLOBAL_SUBSCRIPTION) {
            if ($visitorPassword === null || $visitorPassword === '') {
                throw new OwnerVisitVerificationRequired($user->phone_number, $user->full_name, $funding->billingSource);
            }
            if (! Hash::check($visitorPassword, $user->password)) {
                throw new OwnerVisitException('كلمة مرور الزائر غير صحيحة. لم يتم تسجيل الدخول.');
            }
        }

        if (! $this->visits->reserveOccupancy($workspace)) {
            throw new OwnerVisitException('مساحة العمل ممتلئة حالياً.');
        }

        try {
            return WorkspaceVisit::create([
                'user_id' => $user->id,
                'walk_in_id' => null,
                'workspace_id' => $workspace->id,
                'subscription_id' => $funding->subscriptionId,
                'workspace_subscription_id' => $funding->workspaceSubscriptionId,
                'billing_source' => $funding->billingSource->value,
                'plan_tier_snapshot' => $funding->planTierSnapshot,
                'status' => VisitStatus::CHECKED_IN->value,
                'source' => VisitSource::OWNER->value,
                'registered_by' => $registeredBy,
                'check_in_at' => now(),
                'active_flag' => 1,
            ])->load(['user', 'subscription.plan', 'workspaceSubscription']);
        } catch (QueryException $exception) {
            if (($exception->errorInfo[1] ?? null) === 1062 || $exception->getCode() === '23000') {
                throw new OwnerVisitException('هذا المستخدم لديه زيارة نشطة بالفعل.');
            }
            throw $exception;
        }
    }
}
