<?php

declare(strict_types=1);

namespace App\Domain\WorkspacePortal\Data;

use App\Http\Requests\WorkspacePortal\WorkspaceUpdateRequest;
use Illuminate\Http\UploadedFile;

final readonly class WorkspaceUpdateData
{
    /**
     * @param  array<string>  $amenities
     * @param  array<UploadedFile>  $galleryImages
     * @param  array<string>  $retainedGalleryImages
     */
    public function __construct(
        public string $name,
        public ?string $description,
        public string $address,
        public float $latitude,
        public float $longitude,
        public ?int $capacity,
        public ?string $openTime,
        public ?string $closeTime,
        public ?string $adminPhone,
        public int $dayCalculationHours,
        public float $hourMultiplier,
        public array $amenities,
        public ?UploadedFile $coverImage,
        public array $galleryImages,
        public array $retainedGalleryImages,
        public ?int $manualOccupancy,
        public string $status,
        public string $checkoutMode,
        public array $drinks,
    ) {}

    public static function fromRequest(WorkspaceUpdateRequest $request): self
    {
        return new self(
            name: $request->string('name')->trim()->value(),
            description: $request->input('description') ? trim((string) $request->input('description')) : null,
            address: $request->string('address')->trim()->value(),
            latitude: $request->float('latitude'),
            longitude: $request->float('longitude'),
            capacity: $request->input('capacity') !== null ? $request->integer('capacity') : null,
            openTime: $request->input('open_time') ? trim((string) $request->input('open_time')) : null,
            closeTime: $request->input('close_time') ? trim((string) $request->input('close_time')) : null,
            adminPhone: $request->input('admin_phone') ? trim((string) $request->input('admin_phone')) : null,
            dayCalculationHours: $request->integer('day_calculation_hours', 8),
            hourMultiplier: (float) $request->input('hour_multiplier', $request->user()?->ownedWorkspace?->hour_multiplier ?? 1.0),
            amenities: $request->input('amenities', []),
            coverImage: $request->file('cover_image'),
            galleryImages: $request->file('gallery_images', []),
            retainedGalleryImages: $request->input('retained_gallery_images', []),
            manualOccupancy: $request->input('manual_occupancy') !== null ? $request->integer('manual_occupancy') : null,
            status: $request->string('status')->value(),
            checkoutMode: in_array($request->input('checkout_mode'), ['DIRECT', 'APPROVAL'], true)
                ? (string) $request->input('checkout_mode')
                : 'DIRECT',
            drinks: $request->input('drinks', []),
        );
    }
}
