<?php

declare(strict_types=1);

namespace App\Domain\Profile\Actions;

use App\Domain\Profile\Contracts\ProfileRepositoryInterface;
use App\Domain\Profile\Data\UpdateProfileData;
use App\Domain\Profile\Support\ProfileCompletionSummary;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class UpdateProfileAction
{
    public function __construct(private ProfileRepositoryInterface $profiles) {}

    public function handle(User $user, UpdateProfileData $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $updated = $this->profiles->update($user, $data);
            $summary = ProfileCompletionSummary::forUser($updated);
            $completedAt = $summary->completed ? ($updated->profile_completed_at ?? now()) : null;

            if ($updated->profile_completed_at?->toIso8601String() !== $completedAt?->toIso8601String()) {
                $updated = $this->profiles->setProfileCompletedAt($updated, $completedAt);
            }

            return $updated;
        });
    }
}
