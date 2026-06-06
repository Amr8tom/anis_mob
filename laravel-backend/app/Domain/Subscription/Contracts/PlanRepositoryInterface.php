<?php

declare(strict_types=1);

namespace App\Domain\Subscription\Contracts;

use App\Models\Plan;
use Illuminate\Support\Collection;

interface PlanRepositoryInterface
{
    /**
     * @return Collection<int, Plan>
     */
    public function activePlans(): Collection;
}
