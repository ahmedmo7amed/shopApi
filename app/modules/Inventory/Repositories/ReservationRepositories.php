<?php

namespace App\Modules\Inventory\Repositories;

use App\Modules\Inventory\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationRepository
{
    /**
     * Create a new reservation.
     */
    public function createReservation(array $data): Reservation
    {
        $reservation = new Reservation();
        $reservation->id = (string) Str::uuid();
        $reservation->product_id = $data['product_id'];
        $reservation->warehouse_id = $data['warehouse_id'];
        $reservation->quantity = $data['quantity'];
        $reservation->order_id = $data['order_id'] ?? null;
        $reservation->expires_at = Carbon::parse($data['expires_at']);
        $reservation->status = 'ACTIVE';
        $reservation->save();

        return $reservation;
    }
}
