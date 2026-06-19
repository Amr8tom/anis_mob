<?php

declare(strict_types=1);

namespace App\Domain\Room\Actions;

use App\Domain\Room\Contracts\RoomReservationRepositoryInterface;
use App\Enums\RoomReservationStatus;
use App\Models\RoomReservation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class CancelReservationAction
{
    public function __construct(private RoomReservationRepositoryInterface $reservations) {}

    public function handle(string $reservationId, string $workspaceId): RoomReservation
    {
        $reservation = $this->reservations->findForWorkspace($reservationId, $workspaceId);
        if ($reservation === null) {
            throw new NotFoundHttpException('Reservation not found.');
        }

        if ($reservation->status === RoomReservationStatus::RESERVED) {
            $reservation->update(['status' => RoomReservationStatus::CANCELLED->value]);
        }

        return $reservation->refresh();
    }
}
