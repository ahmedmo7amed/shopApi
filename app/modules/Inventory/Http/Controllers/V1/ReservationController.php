<?php

namespace Modules\Inventory\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\modules\Inventory\Services\ReservationServices;

class ReservationController extends Controller
{
    protected $reservationServices;
    public function __construct(ReservationServices $reservationServices)
    {
        $this->reservationServices = $reservationServices;
    }

    public function expireReservation($reservationId)
    {
        $reservation = $this->reservationServices->getReservationById($reservationId);

        if ($this->reservationServices->isReservationExpired($reservation)) {
            $this->reservationServices->expireReservation($reservation);
            return response()->json(['message' => 'Reservation expired successfully.']);
        }

        return response()->json(['message' => 'Reservation is still valid.'], 400);
    }

    public function releaseReservation($reservationId)
    {
        $reservation = $this->reservationServices->getReservationById($reservationId);

        $this->reservationServices->releaseReservation($reservation);
        return response()->json(['message' => 'Reservation released successfully.']);
    }
    public function cancelReservation($reservationId)
    {
        $reservation = $this->reservationServices->getReservationById($reservationId);

        $this->reservationServices->cancelReservation($reservation);
        return response()->json(['message' => 'Reservation cancelled successfully.']);
    }
    public function completeReservation($reservationId)
    {
        $reservation = $this->reservationServices->getReservationById($reservationId);

        $this->reservationServices->completeReservation($reservation);
        return response()->json(['message' => 'Reservation completed successfully.']);
    }
    public function activateReservation($reservationId)
    {
        $reservation = $this->reservationServices->getReservationById($reservationId);

        $this->reservationServices->activateReservation($reservation);
        return response()->json(['message' => 'Reservation activated successfully.']);
    }

}
