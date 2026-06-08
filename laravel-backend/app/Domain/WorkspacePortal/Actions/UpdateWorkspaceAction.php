<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Actions;

use App\Domain\WorkspacePortal\Contracts\WorkspacePortalRepositoryInterface;
use App\Domain\WorkspacePortal\Data\WorkspaceUpdateData;
use App\Models\Workspace;
use Illuminate\Support\Facades\Storage;

final readonly class UpdateWorkspaceAction
{
    public function __construct(
        private WorkspacePortalRepositoryInterface $repository
    ) {}

    public function handle(Workspace $workspace, WorkspaceUpdateData $data): Workspace
    {
        // 1. Map simple text/numeric attributes
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

        // 2. Handle Cover Image
        if ($data->coverImage !== null) {
            // Delete old cover if it exists on public disk
            if ($workspace->cover_image_url) {
                $oldCoverPath = str_replace('/storage/', '', $workspace->cover_image_url);
                Storage::disk('public')->delete($oldCoverPath);
            }
            $coverPath = $data->coverImage->store("workspaces/{$workspace->id}", 'public');
            $workspace->cover_image_url = '/storage/'.$coverPath;
        }

        // 3. Handle Gallery Images
        $galleryUrls = $data->retainedGalleryImages;
        $oldGalleryUrls = $workspace->gallery_images ?? [];

        // Delete deleted gallery images from disk
        $removedUrls = array_diff($oldGalleryUrls, $data->retainedGalleryImages);
        foreach ($removedUrls as $removedUrl) {
            $removedPath = str_replace('/storage/', '', $removedUrl);
            Storage::disk('public')->delete($removedPath);
        }

        // Save new gallery images
        foreach ($data->galleryImages as $file) {
            $path = $file->store("workspaces/{$workspace->id}/gallery", 'public');
            $galleryUrls[] = '/storage/'.$path;
        }
        $workspace->gallery_images = $galleryUrls;

        // 4. Persist changes via repository
        return $this->repository->updateWorkspace($workspace, $data);
    }
}
