<?php

declare(strict_types=1);

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

final class ListWorkspacesRequest extends FormRequest
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
            'filter' => ['nullable', 'string', 'in:openNow,nearby'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function filterValue(): ?string
    {
        return $this->filled('filter') ? $this->string('filter')->value() : null;
    }

    public function latitudeValue(): ?float
    {
        return $this->filled('latitude') ? (float) $this->input('latitude') : null;
    }

    public function longitudeValue(): ?float
    {
        return $this->filled('longitude') ? (float) $this->input('longitude') : null;
    }

    public function perPageValue(): int
    {
        return min((int) $this->integer('per_page', 20) ?: 20, 100);
    }
}
