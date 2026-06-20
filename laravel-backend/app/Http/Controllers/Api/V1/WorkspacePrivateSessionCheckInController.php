<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspacePrivateSession;
use App\Models\WorkspacePrivateSessionAttendee;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WorkspacePrivateSessionCheckInController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'qr_token' => ['required', 'string', 'max:120'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $normalizedPhone = preg_replace('/\D+/', '', (string) $user->phone_number) ?? '';

        $session = WorkspacePrivateSession::query()
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if ($session === null || $session->status !== 'active') {
            return ApiResponse::error('هذه الجلسة غير متاحة للتسجيل الآن.', 422);
        }

        $attendee = WorkspacePrivateSessionAttendee::query()
            ->where('workspace_private_session_id', $session->id)
            ->where('phone_normalized', $normalizedPhone)
            ->first();

        if ($attendee === null) {
            return ApiResponse::error('هذا الرقم غير مضاف لقائمة حضور الجلسة.', 403);
        }

        if ($attendee->status === 'attended') {
            return ApiResponse::ok([
                'session_id' => $session->id,
                'attendee_id' => $attendee->id,
                'checked_in_at' => $attendee->checked_in_at?->toISOString(),
                'already_checked_in' => true,
            ], 'تم تسجيل حضورك من قبل.');
        }

        $attendee->update([
            'user_id' => $user->id,
            'status' => 'attended',
            'checked_in_at' => now(),
            'checked_in_method' => 'qr',
            'amount_cents' => $attendee->amount_cents > 0 ? $attendee->amount_cents : (int) $session->price_cents,
            'payment_status' => 'paid',
        ]);

        return ApiResponse::created([
            'session_id' => $session->id,
            'attendee_id' => $attendee->id,
            'checked_in_at' => $attendee->checked_in_at?->toISOString(),
            'amount_cents' => $attendee->amount_cents,
        ], 'تم تسجيل حضورك للجلسة.');
    }
}
