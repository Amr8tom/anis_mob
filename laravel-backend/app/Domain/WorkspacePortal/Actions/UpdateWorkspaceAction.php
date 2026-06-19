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
        $disk = (string) config('filesystems.workspace_media_disk');
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
        $workspace->hour_multiplier = $data->hourMultiplier;
        $workspace->checkout_mode = $data->checkoutMode;
        $workspace->amenities = $data->amenities;

        // 2. Handle Cover Image
        if ($data->coverImage !== null) {
            // Delete old cover if it exists on public disk
            if ($workspace->cover_image_url) {
                Storage::disk($disk)->delete($this->mediaPath($workspace->getRawOriginal('cover_image_url')));
            }
            $workspace->cover_image_url = $data->coverImage->store("workspaces/{$workspace->id}", $disk);
        }

        // 3. Handle Gallery Images
        $galleryUrls = array_map($this->mediaPath(...), $data->retainedGalleryImages);
        $oldGalleryUrls = $workspace->gallery_images ?? [];

        // Delete deleted gallery images from disk
        $removedUrls = array_diff($oldGalleryUrls, $data->retainedGalleryImages);
        foreach ($removedUrls as $removedUrl) {
            Storage::disk($disk)->delete($this->mediaPath($removedUrl));
        }

        // Save new gallery images
        foreach ($data->galleryImages as $file) {
            $galleryUrls[] = $file->store("workspaces/{$workspace->id}/gallery", $disk);
        }
        $workspace->gallery_images = $galleryUrls;

        // 4. Persist changes via repository
        return $this->repository->updateWorkspace($workspace, $data);
    }

    private function mediaPath(string $value): string
    {
        $path = parse_url($value, PHP_URL_PATH) ?: $value;

        return ltrim(str_replace('/storage/', '', $path), '/');
    }
}
