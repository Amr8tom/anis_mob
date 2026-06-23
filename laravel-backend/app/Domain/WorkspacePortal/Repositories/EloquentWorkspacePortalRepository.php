<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Repositories;

use App\Domain\WorkspacePortal\Contracts\WorkspacePortalRepositoryInterface as ContractInterface;
use App\Domain\WorkspacePortal\Data\WorkspaceRegistrationData;
use App\Domain\WorkspacePortal\Data\WorkspaceUpdateData;
use App\Enums\WorkspaceLifecycleStatus;
use App\Models\Workspace;
use App\Models\WorkspaceOwner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class EloquentWorkspacePortalRepository implements ContractInterface
{
    public function createOwnerAndWorkspace(WorkspaceRegistrationData $data): Workspace
    {
        return DB::transaction(function () use ($data) {
            $owner = WorkspaceOwner::create([
                'full_name' => $data->fullName,
                'phone_number' => $data->phoneNumber,
                'email' => null,
                'password' => $data->password,
                'status' => 'active',
            ]);

            $disk = (string) config('filesystems.workspace_media_disk');
            $coverImageUrl = null;
            if ($data->coverImage) {
                $coverImageUrl = $data->coverImage->store('workspaces', $disk);
            }

            $galleryImagesUrls = [];
            foreach ($data->galleryImages as $image) {
                if ($image) {
                    $galleryImagesUrls[] = $image->store('workspaces', $disk);
                }
            }

            $workspace = Workspace::create([
                'workspace_owner_id' => $owner->id,
                'name' => $data->workspaceName,
                'address' => $data->address,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'day_calculation_hours' => $data->dayCalculationHours,
                'admin_phone' => $data->whatsappNumber,
                'qr_token' => (string) Str::uuid7(),
                // Self-registrations are held for admin review: hidden from the
                // mobile app and unable to accept check-ins until approved.
                'lifecycle_status' => WorkspaceLifecycleStatus::PENDING,
                'is_active' => false,
                'description' => $data->description,
                'capacity' => $data->capacity,
                'open_time' => $data->openTime,
                'close_time' => $data->closeTime,
                'amenities' => $data->amenities,
                'cover_image_url' => $coverImageUrl,
                'gallery_images' => $galleryImagesUrls,
            ]);

            if (! empty($data->drinks)) {
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
            $workspace->hour_multiplier = $data->hourMultiplier;
            $workspace->checkout_mode = $data->checkoutMode;
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
            ->where('workspace_owner_id', $ownerId)
            ->first();
    }
}
