<?php

declare(strict_types=1);

namespace App\Http\Requests\DeviceToken;

use Illuminate\Foundation\Http\FormRequest;

final class StoreDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'max:512'],
            'platform' => ['nullable', 'string', 'in:android,ios'],
        ];
    }
}
