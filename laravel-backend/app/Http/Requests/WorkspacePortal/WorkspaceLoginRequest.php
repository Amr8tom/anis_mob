<?php

declare(strict_types=1);

namespace App\Http\Requests\WorkspacePortal;

use Illuminate\Foundation\Http\FormRequest;

final class WorkspaceLoginRequest extends FormRequest
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
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
