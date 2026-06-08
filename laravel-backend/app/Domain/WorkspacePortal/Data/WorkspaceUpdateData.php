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
        public array $amenities,
        public ?UploadedFile $coverImage,
        public array $galleryImages,
        public array $retainedGalleryImages,
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
            amenities: $request->input('amenities', []),
            coverImage: $request->file('cover_image'),
            galleryImages: $request->file('gallery_images', []),
            retainedGalleryImages: $request->input('retained_gallery_images', []),
        );
    }
}
