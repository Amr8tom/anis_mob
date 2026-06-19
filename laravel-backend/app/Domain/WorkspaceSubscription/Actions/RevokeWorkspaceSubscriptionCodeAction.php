<?php

declare(strict_types=1);

namespace App\Domain\WorkspaceSubscription\Actions;

use App\Domain\WorkspaceSubscription\Contracts\WorkspaceSubscriptionCodeRepositoryInterface;
use App\Enums\WorkspaceSubscriptionCodeStatus;
use App\Models\WorkspaceSubscriptionCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class RevokeWorkspaceSubscriptionCodeAction
{
    public function __construct(private WorkspaceSubscriptionCodeRepositoryInterface $codes) {}

    public function handle(string $codeId, string $workspaceId): WorkspaceSubscriptionCode
    {
        return DB::transaction(function () use ($codeId, $workspaceId): WorkspaceSubscriptionCode {
            $code = $this->codes->lockForWorkspace($codeId, $workspaceId);
            if ($code === null) {
                throw new NotFoundHttpException('Activation code not found.');
            }

            if ($code->status !== WorkspaceSubscriptionCodeStatus::UNUSED) {
                throw ValidationException::withMessages(['code' => 'يمكن إلغاء الأكواد غير المستخدمة فقط.']);
            }

            $code->update([
                'status' => WorkspaceSubscriptionCodeStatus::REVOKED->value,
                'revoked_at' => now(),
            ]);

            return $code;
        });
    }
}
