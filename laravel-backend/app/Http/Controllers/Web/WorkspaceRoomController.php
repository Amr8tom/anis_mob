<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Domain\Room\Actions\CancelReservationAction;
use App\Domain\Room\Actions\CreateReservationAction;
use App\Domain\Room\Actions\CreateRoomAction;
use App\Domain\Room\Actions\DeactivateRoomAction;
use App\Domain\Room\Actions\DeleteRoomAction;
use App\Domain\Room\Actions\UpdateRoomAction;
use App\Domain\Room\Contracts\RoomClientRepositoryInterface;
use App\Domain\Room\Contracts\RoomRepositoryInterface;
use App\Domain\Room\Contracts\RoomReservationRepositoryInterface;
use App\Domain\Room\Data\CreateReservationData;
use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\RoomClient;
use App\Models\User;
use App\Models\WorkspaceWalkIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkspaceRoomController extends Controller
{
    public function index(
        RoomRepositoryInterface $rooms,
        RoomReservationRepositoryInterface $reservations,
        RoomClientRepositoryInterface $clients,
    ): View {
        $workspace = Auth::user()->ownedWorkspace;

        return view('workspace.rooms.index', [
            'workspace' => $workspace,
            'rooms' => $rooms->allForWorkspace($workspace->id),
            'activeRooms' => $rooms->activeForWorkspace($workspace->id),
            'upcoming' => $reservations->upcomingForWorkspace($workspace->id, 100),
            'clients' => $clients->paginateForWorkspace($workspace->id, 15),
        ]);
    }

    public function storeRoom(Request $request, CreateRoomAction $action): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'hourly_price_pounds' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $action->handle(
            $workspace->id,
            $validated['name'],
            (int) round(((float) $validated['hourly_price_pounds']) * 100),
            $validated['note'] ?? null,
        );

        return back()->with('success', 'تمت إضافة الغرفة.');
    }

    public function updateRoom(Request $request, string $room, UpdateRoomAction $action, RoomRepositoryInterface $rooms): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $rooms->findForWorkspace($room, $workspace->id);
        abort_if($model === null, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'hourly_price_pounds' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $action->handle(
            $model,
            $validated['name'],
            (int) round(((float) $validated['hourly_price_pounds']) * 100),
            (bool) ($validated['is_active'] ?? true),
            $validated['note'] ?? null,
        );

        return back()->with('success', 'تم تحديث الغرفة.');
    }

    public function deactivateRoom(string $room, DeactivateRoomAction $action, RoomRepositoryInterface $rooms): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $rooms->findForWorkspace($room, $workspace->id);
        abort_if($model === null, 404);

        $action->handle($model);

        return back()->with('success', 'تم إيقاف الغرفة.');
    }

    public function deleteRoom(string $room, DeleteRoomAction $action, RoomRepositoryInterface $rooms): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $rooms->findForWorkspace($room, $workspace->id);
        abort_if($model === null, 404);

        $action->handle($model);

        return back()->with('success', 'تم حذف الغرفة وكل حجوزاتها نهائياً.');
    }

    public function storeReservation(Request $request, CreateReservationAction $action): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;
        $validated = $request->validate([
            'room_id' => ['required', 'uuid'],
            'client_name' => ['nullable', 'string', 'max:120'],
            'client_phone' => ['required', 'string', 'max:30'],
            'client_note' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:1440'],
            'note' => ['nullable', 'string', 'max:255'],
            'recurring' => ['nullable', 'boolean'],
            'weekdays' => ['nullable', 'array'],
            'weekdays.*' => ['integer', 'between:0,6'],
            'until' => ['nullable', 'date', 'after_or_equal:date'],
        ]);

        $startsAt = Carbon::parse($validated['date'].' '.$validated['start_time']);
        $clientPhone = trim($validated['client_phone']);
        $clientName = trim((string) ($validated['client_name'] ?? ''));

        if ($clientName === '') {
            $clientName = $this->resolveReservableClientName($workspace->id, $clientPhone);
        }

        if ($clientName === '') {
            return back()
                ->withErrors(['client_name' => 'اكتب اسم العميل أول مرة. بعد حفظه يمكنك الحجز برقم الهاتف فقط.'])
                ->withInput();
        }

        $data = new CreateReservationData(
            roomId: $validated['room_id'],
            clientName: $clientName,
            clientPhone: $clientPhone,
            clientNote: $validated['client_note'] ?? null,
            startsAt: $startsAt,
            durationMinutes: (int) $validated['duration_minutes'],
            note: $validated['note'] ?? null,
            recurring: (bool) ($validated['recurring'] ?? false),
            weekdays: array_map('intval', $validated['weekdays'] ?? []),
            until: isset($validated['until']) ? Carbon::parse($validated['until']) : null,
        );

        try {
            $result = $action->handle($workspace->id, (string) Auth::id(), $data);
        } catch (ApiException $e) {
            return back()->withErrors(['room_id' => $e->getMessage()])->withInput();
        }

        $message = "تم إنشاء {$result->createdCount()} حجز.";
        if ($result->skippedCount() > 0) {
            $message .= ' تم تخطّي '.$result->skippedCount().' موعد لتعارضها مع حجوزات قائمة: '.implode('، ', $result->skipped);
        }

        return back()->with('success', $message);
    }

    public function cancelReservation(string $reservation, CancelReservationAction $action): RedirectResponse
    {
        $workspace = Auth::user()->ownedWorkspace;

        $action->handle($reservation, $workspace->id);

        return back()->with('success', 'تم إلغاء الحجز.');
    }

    public function showClient(string $client, RoomClientRepositoryInterface $clients, RoomReservationRepositoryInterface $reservations): View
    {
        $workspace = Auth::user()->ownedWorkspace;
        $model = $clients->findForWorkspace($client, $workspace->id);
        abort_if($model === null, 404);

        return view('workspace.rooms.client', [
            'workspace' => $workspace,
            'client' => $model,
            'reservations' => $reservations->paginateForClient($model->id, 20),
        ]);
    }

    private function resolveReservableClientName(string $workspaceId, string $phone): string
    {
        $existingRoomClient = RoomClient::query()
            ->where('workspace_id', $workspaceId)
            ->where('phone', $phone)
            ->value('name');

        if (filled($existingRoomClient)) {
            return trim((string) $existingRoomClient);
        }

        $walkIn = WorkspaceWalkIn::query()
            ->where('workspace_id', $workspaceId)
            ->where('phone_number', $phone)
            ->value('full_name');

        if (filled($walkIn)) {
            return trim((string) $walkIn);
        }

        $appUser = User::query()
            ->where('phone_number', $phone)
            ->value('full_name');

        return filled($appUser) ? trim((string) $appUser) : '';
    }
}
