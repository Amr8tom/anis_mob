<?php

declare(strict_types=1);

namespace App\Http\Requests\WorkspacePortal;

use Illuminate\Foundation\Http\FormRequest;

final class SendWorkspaceNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:2048'],
            'target_type' => ['required', 'in:user,all_visitors,selected,public_session,private_session'],
            'user_ids' => ['required_if:target_type,user,selected', 'array'],
            'user_ids.*' => ['uuid'],
            'session_id' => ['required_if:target_type,public_session', 'required_if:target_type,private_session', 'uuid'],
        ];
    }
}
