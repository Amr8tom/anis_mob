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

            $coverImageUrl = null;
            if ($data->coverImage) {
                $coverImageUrl = '/storage/' . $data->coverImage->store('workspaces', 'public');
            }

            $galleryImagesUrls = [];
            foreach ($data->galleryImages as $image) {
                if ($image) {
                    $galleryImagesUrls[] = '/storage/' . $image->store('workspaces', 'public');
                }
            }

            $workspace = Workspace::create([
                'owner_id' => $user->id,
                'name' => $data->workspaceName,
                'address' => $data->address,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'day_calculation_hours' => $data->dayCalculationHours,
                'admin_phone' => $data->whatsappNumber,
                'qr_token' => (string) Str::uuid(),
                'is_active' => true,
                'description' => $data->description,
                'capacity' => $data->capacity,
                'open_time' => $data->openTime,
                'close_time' => $data->closeTime,
                'amenities' => $data->amenities,
                'cover_image_url' => $coverImageUrl,
                'gallery_images' => $galleryImagesUrls,
            ]);

            if (!empty($data->drinks)) {
                $workspace->drinks()->createMany($data->drinks);
            }

            return $workspace;
        });
    }

    public function updateWorkspace(Workspace $workspace, WorkspaceUpdateData $data): Workspace
    {
        return DB::transaction(function () use ($workspace, $data) {
            $workspace->name = $data->name;
            $workspace->description = $data->description;
            $workspace->address = $data->address;
            $workspace->latitude = $data->latitude;
            $workspace->longitude = $data->longitude;
            $workspace->capacity = $data->capacity;
            $workspace->open_time = $data->openTime;
            $workspace->close_time = $data->closeTime;
            $workspace->admin_phone = $data->adminPhone;
            $workspace->day_calculation_hours = $data->dayCalculationHours;
            $workspace->amenities = $data->amenities;
            $workspace->manual_occupancy = $data->manualOccupancy;
            $workspace->status = $data->status;

            $workspace->save();

            // Sync Drinks
            $incomingIds = collect($data->drinks)->pluck('id')->filter()->toArray();
            
            // Delete removed drinks
            $workspace->drinks()->whereNotIn('id', $incomingIds)->delete();
            
            // Create or update drinks
            foreach ($data->drinks as $drinkData) {
                $workspace->drinks()->updateOrCreate(
                    ['id' => $drinkData['id'] ?? null],
                    [
                        'name' => $drinkData['name'],
                        'icon' => $drinkData['icon'],
                        'price_cents' => $drinkData['price_cents'],
                    ]
                );
            }

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
