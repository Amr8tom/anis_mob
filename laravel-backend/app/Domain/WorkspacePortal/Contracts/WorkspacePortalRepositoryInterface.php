<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Contracts;

use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Domain\WorkspacePortal\Data\WorkspaceUpdateData;
use App\Models\Workspace;

interface WorkspacePortalRepositoryInterface
{
    public function createOwnerAndWorkspace(WorkspaceRegistrationData $data): Workspace;

    public function updateWorkspace(Workspace $workspace, WorkspaceUpdateData $data): Workspace;

    public function findByOwnerId(string $ownerId): ?Workspace;
}
