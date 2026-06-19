<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

final class ActivatePlanCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy is handled in controller
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:32'],
        ];
    }
}
