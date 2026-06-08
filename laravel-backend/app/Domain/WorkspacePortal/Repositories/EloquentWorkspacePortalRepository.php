<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Repositories;

use App\Domain\WorkspacePortal\Contracts\WorkspacePortalRepositoryInterface as ContractInterface;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Domain\WorkspacePortal\Data\WorkspaceUpdateData;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class EloquentWorkspacePortalRepository implements ContractInterface
{
    public function createOwnerAndWorkspace(WorkspaceRegistrationData $data): Workspace
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'full_name' => $data->fullName,
                'phone_number' => $data->phoneNumber,
                'whatsapp_number' => $data->whatsappNumber,
                'password' => Hash::make($data->password),
                'role' => UserRole::WORKSPACE_OWNER,
            ]);

            return Workspace::create([
                'owner_id' => $user->id,
                'name' => $data->workspaceName,
                'address' => $data->address,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'day_calculation_hours' => $data->dayCalculationHours,
                'admin_phone' => $data->whatsappNumber,
                'qr_token' => (string) Str::uuid(),
                'is_active' => true,
            ]);
        });
    }

    public function updateWorkspace(Workspace $workspace, WorkspaceUpdateData $data): Workspace
    {
        // Text fields and attributes will be updated.
        // File handling (uploading/deleting on disk) is coordinated by the Action,
        // which calls save or passes the resolved file paths to this repository.
        // Wait, to keep it clean, the Action will update file attributes on the model,
        // and then call this repository method to save the model and basic data.
        return DB::transaction(function () use ($workspace) {
            $workspace->save();

            return $workspace->fresh();
        });
    }

    public function findByOwnerId(string $ownerId): ?Workspace
    {
        return Workspace::query()
            ->where('owner_id', $ownerId)
            ->first();
    }
}
