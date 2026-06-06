<?php

declare(strict_types=1);

namespace App\Http\Requests\Buddy;

use Illuminate\Foundation\Http\FormRequest;

final class ListBuddySessionsRequest extends FormRequest
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
            'university' => ['nullable', 'string', 'max:120'],
            'subject' => ['nullable', 'string', 'max:120'],
            'filter' => ['nullable', 'string', 'in:availableNow,open'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function universityValue(): ?string
    {
        return $this->filled('university') ? $this->string('university')->value() : null;
    }

    public function subjectValue(): ?string
    {
        return $this->filled('subject') ? $this->string('subject')->value() : null;
    }

    public function filterValue(): ?string
    {
        return $this->filled('filter') ? $this->string('filter')->value() : null;
    }

    public function perPageValue(): int
    {
        return min((int) $this->integer('per_page', 20) ?: 20, 100);
    }
}
