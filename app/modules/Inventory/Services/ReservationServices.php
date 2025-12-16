<?php

namespace App\modules\Inventory\Services;

use App\Modules\Inventory\Repositories\ReservationRepositories;
use App\Modules\Inventory\Models\Reservation;
use Carbon\Carbon;

class ReservationServices
{
    protected ReservationRepositories $reservationRepository;

    public function __construct(ReservationRepositories $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * Create a new reservation.
     */
    public function createReservation(array $data): Reservation
    {
        // Additional business logic can be added here before creating the reservation

        return $this->reservationRepository->createReservation($data);
    }

    public function isReservationExpired(Reservation $reservation): bool
    {
        return Carbon::now()->greaterThan($reservation->expires_at);
    }

    public function cancelReservation(Reservation $reservation): void
    {
        $reservation->status = 'CANCELLED';
        $reservation->save();
    }

    public function completeReservation(Reservation $reservation): void
    {
        $reservation->status = 'COMPLETED';
        $reservation->save();
    }
    public function activateReservation(Reservation $reservation): void
    {
        $reservation->status = 'ACTIVE';
        $reservation->save();
    }
    public function expireReservation(Reservation $reservation): void
    {
        $reservation->status = 'EXPIRED';
        $reservation->save();
    }
    public function releaseReservation(Reservation $reservation): void
    {
        $reservation->status = 'RELEASED';
        $reservation->save();
    }
    public function getReservationById(string $reservationId): ?Reservation
    {
        return Reservation::find($reservationId);
    }

}
