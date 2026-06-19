<?php

declare(strict_types=1);

namespace App\Domain\Attendance\Data;

use App\Enums\BillingSource;
use App\Enums\PlanTier;

/**
 * The funding source chosen for a visit at check-in time. Exactly one of the two
 * subscription ids is non-null (or both null for FREE).
 */
final readonly class VisitFunding
{
    public function __construct(
        public BillingSource $billingSource,
        public ?string $workspaceSubscriptionId,
        public ?string $subscriptionId,
        public ?string $planTierSnapshot,   // legacy reporting fallback only
    ) {}

    public static function free(): self
    {
        return new self(BillingSource::FREE, null, null, PlanTier::FREE->value);
    }

    public static function workspace(string $workspaceSubscriptionId): self
    {
        return new self(BillingSource::WORKSPACE_SUBSCRIPTION, $workspaceSubscriptionId, null, null);
    }

    public static function global(string $subscriptionId, string $planTier): self
    {
        return new self(BillingSource::GLOBAL_SUBSCRIPTION, null, $subscriptionId, $planTier);
    }

    public function isPaid(): bool
    {
        return $this->billingSource !== BillingSource::FREE;
    }
}
