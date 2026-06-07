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
            'full_name' => ['sometimes', 'string', 'min:2', 'max:120'],
            'email' => ['sometimes', 'nullable', 'email', 'max:120', 'unique:users,email,'.$this->user()?->id],
            'university' => ['sometimes', 'nullable', 'string', 'max:120'],
            'study_field' => ['sometimes', 'nullable', 'string', 'max:120'],
            'gender' => ['sometimes', 'nullable', 'string', 'in:male,female,MALE,FEMALE'],
            'avatar_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'avatar_color_key' => ['sometimes', 'string', 'max:20'],
            'availability' => ['sometimes', 'string', 'in:online,busy,offline,ONLINE,BUSY,OFFLINE'],
            'interests' => ['sometimes', 'array', 'max:30'],
            'interests.*' => ['string', 'min:1', 'max:60', 'distinct'],
        ];
    }
}
