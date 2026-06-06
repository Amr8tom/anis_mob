<?php

declare(strict_types=1);

namespace App\Http\Requests\Buddy;

use Illuminate\Foundation\Http\FormRequest;

final class CreateBuddySessionRequest extends FormRequest
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
            'topic' => ['required', 'string', 'max:120'],
            'subject' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
            'rules' => ['nullable', 'array', 'max:20'],
            'rules.*' => ['string', 'max:200'],
            // Trust only the workspace id; workspaceName/workspaceAddress from the client are ignored.
            'workspaceId' => ['required', 'uuid', 'exists:workspaces,id'],
            'startTime' => ['required', 'date', 'after:now'],
            'maxCapacity' => ['required', 'integer', 'min:2', 'max:100'],
            'gift' => ['nullable', 'string', 'max:120'],
        ];
    }
}
