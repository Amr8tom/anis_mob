<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Models\WorkspacePlan;
use App\Models\WorkspaceSubscriptionCode;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

final readonly class GenerateWorkspaceSubscriptionCodeAction
{
    public function __construct(private WorkspaceSubscriptionCodeRepositoryInterface $codes) {}

    /**
     * Generate N unused codes for a plan. Does NOT create subscription rows (R11).
     *
     * @return array<int, WorkspaceSubscriptionCode>
     */
    public function handle(WorkspacePlan $plan, string $ownerId, int $count = 1, ?\DateTimeInterface $expiresAt = null): array
    {
        if (! $plan->is_active) {
            throw ValidationException::withMessages(['workspace_plan_id' => 'لا يمكن إصدار أكواد لباقة موقوفة.']);
        }

        $created = [];

        for ($i = 0; $i < $count; $i++) {
            $created[] = $this->createWithCollisionRetry($plan, $ownerId, $expiresAt);
        }

        return $created;
    }

    private function createWithCollisionRetry(WorkspacePlan $plan, string $ownerId, ?\DateTimeInterface $expiresAt): WorkspaceSubscriptionCode
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            try {
                return $this->codes->create([
                    'workspace_id' => $plan->workspace_id,
                    'workspace_plan_id' => $plan->id,
                    'code' => $this->uniqueCode(),
                    'status' => 'UNUSED',
                    'created_by_owner_id' => $ownerId,
                    'expires_at' => $expiresAt,
                ]);
            } catch (QueryException $exception) {
                if (! $this->isUniqueViolation($exception) || $attempt === 5) {
                    throw $exception;
                }
            }
        }

        throw new \RuntimeException('Failed to generate a unique workspace subscription code.');
    }

    private function uniqueCode(): string
    {
        // Unambiguous alphabet (no 0/O/1/I), format WSP-XXXX-XXXX. Retry on collision.
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $attempts = 0;
        do {
            $part = static fn (): string => collect(range(1, 4))
                ->map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])
                ->implode('');
            $code = 'WSP-'.$part().'-'.$part();
            $attempts++;
        } while ($this->codes->existsByCode($code) && $attempts < 3);

        if ($attempts >= 3 && $this->codes->existsByCode($code)) {
            throw new \RuntimeException('Failed to generate a unique workspace subscription code after 3 attempts.');
        }

        return $code;
    }

    private function isUniqueViolation(QueryException $exception): bool
    {
        return ($exception->errorInfo[1] ?? null) === 1062 || $exception->getCode() === '23000';
    }
}
