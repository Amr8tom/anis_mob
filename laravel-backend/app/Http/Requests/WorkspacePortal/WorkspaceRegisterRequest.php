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
            'phone_number' => ['required', 'string', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:6', 'max:72'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'workspace_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'day_calculation_hours' => ['required', 'integer', 'min:1', 'max:24'],
        ];
    }
}
