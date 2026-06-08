<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Actions;

use App\Domain\WorkspacePortal\Contracts\WorkspacePortalRepositoryInterface;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Models\Workspace;

final readonly class RegisterWorkspaceAction
{
    public function __construct(
        private WorkspacePortalRepositoryInterface $repository
    ) {}

    public function handle(WorkspaceRegistrationData $data): Workspace
    {
        return $this->repository->createOwnerAndWorkspace($data);
    }
}
