<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Repositories;

use App\Domain\Subscription\Contracts\PlanRepositoryInterface;
use App\Models\Plan;
use Illuminate\Support\Collection;

final class EloquentPlanRepository implements PlanRepositoryInterface
{
    public function activePlans(): Collection
    {
        return Plan::query()
            ->where('is_active', true)
            ->orderBy('price_cents')
            ->get();
    }
}
