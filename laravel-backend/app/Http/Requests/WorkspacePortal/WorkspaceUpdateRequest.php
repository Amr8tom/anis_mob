<?php

declare(strict_types=1);

namespace App\Http\Requests\WorkspacePortal;

use Illuminate\Foundation\Http\FormRequest;

final class WorkspaceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'open_time' => ['nullable', 'string', 'max:10'],
            'close_time' => ['nullable', 'string', 'max:10'],
            'admin_phone' => ['nullable', 'string', 'max:30'],
            'day_calculation_hours' => ['required', 'integer', 'min:1', 'max:24'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:10240'],
            'retained_gallery_images' => ['nullable', 'array'],
            'retained_gallery_images.*' => ['string'],
            'manual_occupancy' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:OPEN,BUSY,FULL,CLOSED'],
            'checkout_mode' => ['nullable', 'string', 'in:DIRECT,APPROVAL'],
            'drinks' => ['nullable', 'array'],
            'drinks.*.id' => ['nullable', 'uuid'],
            'drinks.*.name' => ['required', 'string', 'max:255'],
            'drinks.*.icon' => ['required', 'string', 'max:50'],
            'drinks.*.price_cents' => ['required', 'integer', 'min:0'],
            'hour_multiplier' => ['sometimes', 'numeric', 'decimal:0,2', 'min:0', 'max:10'],
        ];
    }
}
