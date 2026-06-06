<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Accept a single `login` identifier (phone OR email). For backward
     * compatibility, also accept the legacy `phone_number` / `email` keys —
     * at least one must be present.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->filled('login')) {
            $this->merge([
                'login' => (string) ($this->input('phone_number') ?? $this->input('email') ?? ''),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required' => 'Please provide your phone number or email.',
        ];
    }
}
