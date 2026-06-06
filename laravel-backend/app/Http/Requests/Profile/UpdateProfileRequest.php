<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'string', 'max:120'],
            'university' => ['sometimes', 'nullable', 'string', 'max:120'],
            'study_field' => ['sometimes', 'nullable', 'string', 'max:120'],
            'avatar_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'avatar_color_key' => ['sometimes', 'string', 'max:20'],
            'availability' => ['sometimes', 'string', 'in:online,busy,offline,ONLINE,BUSY,OFFLINE'],
            'interests' => ['sometimes', 'array', 'max:30'],
            'interests.*' => ['string', 'max:60'],
        ];
    }

    /**
     * Normalised, whitelisted attributes for the model update.
     *
     * @return array<string, mixed>
     */
    public function attributesForUpdate(): array
    {
        $data = $this->only([
            'full_name', 'university', 'study_field', 'avatar_url', 'avatar_color_key', 'interests',
        ]);

        if ($this->filled('availability')) {
            $data['availability'] = strtoupper($this->string('availability')->value());
        }

        return $data;
    }
}
