<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:120'],
            'phone_number' => ['required', 'string', 'max:30', 'unique:users,phone_number'],
            // email/gender/study_field optional: current Flutter omits them, future builds will send them.
            'email' => ['nullable', 'email', 'max:120', 'unique:users,email'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->max(72)->mixedCase()->numbers()],
            'password_confirmation' => ['required', 'string'],
            'gender' => ['nullable', 'string', 'in:male,female,MALE,FEMALE'],
            'study_field' => ['nullable', 'string', 'max:120'],
        ];
    }
}
