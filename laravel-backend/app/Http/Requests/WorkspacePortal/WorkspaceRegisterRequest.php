<?php

declare(strict_types=1);

namespace App\Http\Requests\WorkspacePortal;

use Illuminate\Foundation\Http\FormRequest;

final class WorkspaceRegisterRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'unique:workspace_owners,phone_number'],
            'password' => ['required', 'string', 'min:6', 'max:72'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'workspace_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'day_calculation_hours' => ['required', 'integer', 'min:1', 'max:24'],
            'description' => ['nullable', 'string', 'max:2000'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'open_time' => ['nullable', 'string', 'max:10'],
            'close_time' => ['nullable', 'string', 'max:10'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'max:5120'],
            'drinks' => ['nullable', 'array'],
            'drinks.*.name' => ['required', 'string', 'max:255'],
            'drinks.*.icon' => ['required', 'string', 'max:50'],
            'drinks.*.price_cents' => ['required', 'integer', 'min:0'],
        ];
    }
}
