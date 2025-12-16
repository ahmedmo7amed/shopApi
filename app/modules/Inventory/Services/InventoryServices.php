<?php

// app/Modules/Inventory/Services/InventoryService.php
namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Repositories\StockRepository;
use App\Modules\Inventory\Repositories\ReservationRepository;
use App\Modules\Inventory\Repositories\StockTransactionRepository;
use Modules\Product\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Facades\DB;

class InventoryService
{
    protected StockRepository $stockRepository;
    protected ReservationRepository $reservationRepository;
    protected StockTransactionRepository $transactionRepository;

    public function __construct(
        StockRepository $stockRepository,
        ReservationRepository $reservationRepository,
        StockTransactionRepository $transactionRepository
    ) {
        $this->stockRepository = $stockRepository;
        $this->reservationRepository = $reservationRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Reserve stock for order
     */
    public function reserveStock($productId, $warehouseId, $quantity, $orderId = null, $expiresInMinutes = 10)
    {
        // Validate product and warehouse exist
        $product = Product::find($productId);
        $warehouse = Warehouse::find($warehouseId);

        if (!$product || !$warehouse) {
            throw new \Exception("Product or warehouse not found");
        }

        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $orderId, $expiresInMinutes) {

            // Lock stock row for update
            $stock = $this->stockRepository->findForUpdate($productId, $warehouseId);
            if (!$stock) {
                throw new \Exception("Stock record not found");
            }
            // Check availability
            $available = $stock->quantity_on_hand - $stock->quantity_reserved;

            if ($available < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$available}, Requested: {$quantity}");
            }
            // Update reserved quantity
            $stock->quantity_reserved += $quantity;
            $stock->version += 1;
            $stock->save();
            // Create reservation
            $reservation = $this->reservationRepository->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'quantity' => $quantity,
                'order_id' => $orderId,
                'expires_at' => now()->addMinutes($expiresInMinutes),
                'status' => 'ACTIVE'
            ]);
            // Log transaction
            $this->transactionRepository->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'RESERVE',
                'amount' => $quantity,
                'reason' => 'Order reservation',
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'order_id' => $orderId
                ],
                'created_by' => auth()->id()
            ]);
            return $reservation;
        });
    }

    /**
     * Release a reservation
     */
    public function releaseReservation($reservationId)
    {
        $reservation = $this->reservationRepository->find($reservationId);

        if (!$reservation || $reservation->status !== 'ACTIVE') {
            return false;
        }

        return DB::transaction(function () use ($reservation) {
            // Update stock
            $this->stockRepository->updateQuantities(
                $reservation->product_id,
                $reservation->warehouse_id,
                0, // on_hand delta
                -$reservation->quantity // reserved delta
            );
            // Update reservation status
            $this->reservationRepository->updateStatus($reservation->id, 'RELEASED');
            // Log transaction
            $this->transactionRepository->create([
                'product_id' => $reservation->product_id,
                'warehouse_id' => $reservation->warehouse_id,
                'type' => 'RELEASE',
                'amount' => $reservation->quantity,
                'reason' => 'Reservation released',
                'created_by' => auth()->id()
            ]);
            return true;
        });
    }

    /**
     * Commit reservation (ship order)
     */
    public function commitReservation($reservationId)
    {
        $reservation = $this->reservationRepository->find($reservationId);

        if (!$reservation || $reservation->status !== 'ACTIVE') {
            return false;
        }
        return DB::transaction(function () use ($reservation) {

            // Update stock
            $this->stockRepository->updateQuantities(
                $reservation->product_id,
                $reservation->warehouse_id,
                -$reservation->quantity, // on_hand delta (negative)
                -$reservation->quantity // reserved delta (negative)
            );
            // Update reservation status
            $this->reservationRepository->updateStatus($reservation->id, 'RELEASED');
            // Log transaction
            $this->transactionRepository->create([
                'product_id' => $reservation->product_id,
                'warehouse_id' => $reservation->warehouse_id,
                'type' => 'OUTBOUND',
                'amount' => $reservation->quantity,
                'reason' => 'Order shipped',
                'metadata' => [
                    'order_id' => $reservation->order_id
                ],
                'created_by' => auth()->id()
            ]);
            return true;
        });
    }

    /**
     * Add stock (inbound)
     */
    public function addStock($productId, $warehouseId, $quantity, $reason = 'Stock replenishment')
    {
        $product = Product::find($productId);
        $warehouse = Warehouse::find($warehouseId);

        if (!$product || !$warehouse) {
            throw new \Exception("Product or warehouse not found");
        }

        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $reason) {

            // Get or create stock record
            $stock = $this->stockRepository->getOrCreateStock($productId, $warehouseId);
            // Lock for update
            $stock = $this->stockRepository->findForUpdate($productId, $warehouseId);
            // Update quantities
            $stock->quantity_on_hand += $quantity;
            $stock->version += 1;
            $stock->save();
            // Log transaction
            $this->transactionRepository->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'INBOUND',
                'amount' => $quantity,
                'reason' => $reason,
                'created_by' => auth()->id()
            ]);

            return $stock;
        });
    }

    /**
     * Adjust stock (manual adjustment)
     */
    public function adjustStock($productId, $warehouseId, $newOnHand, $newReserved, $reason)
    {
        $stock = $this->stockRepository->findForUpdate($productId, $warehouseId);

        if (!$stock) {
            throw new \Exception("Stock record not found");
        }

        $onHandDelta = $newOnHand - $stock->quantity_on_hand;
        $reservedDelta = $newReserved - $stock->quantity_reserved;

        return DB::transaction(function () use ($stock, $newOnHand, $newReserved, $onHandDelta, $reservedDelta, $reason, $productId, $warehouseId) {
            // Update stock
            $stock->quantity_on_hand = $newOnHand;
            $stock->quantity_reserved = $newReserved;
            $stock->version += 1;
            $stock->save();
            // Log transaction
            $this->transactionRepository->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'ADJUSTMENT',
                'amount' => $onHandDelta,
                'reason' => $reason,
                'metadata' => [
                    'old_on_hand' => $stock->quantity_on_hand - $onHandDelta,
                    'new_on_hand' => $newOnHand,
                    'old_reserved' => $stock->quantity_reserved - $reservedDelta,
                    'new_reserved' => $newReserved
                ],
                'created_by' => auth()->id()
            ]);
            return $stock;
        });
    }

    /**
     * Get stock availability
     */
    public function getAvailability($productId, $warehouseId = null)
    {
        if ($warehouseId) {
            $available = $this->stockRepository->getAvailableStock($productId, $warehouseId);
            return [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'available' => $available,
                'warehouse_specific' => true
            ];
        }
        // Get all warehouses for this product
        $stocks = $this->stockRepository->getStockByProduct($productId);

        $totalAvailable = 0;
        $warehouses = [];

        foreach ($stocks as $stock) {
            $available = $stock->available;
            $totalAvailable += $available;

            $warehouses[] = [
                'warehouse_id' => $stock->warehouse_id,
                'warehouse_name' => $stock->warehouse->name,
                'available' => $available,
                'on_hand' => $stock->quantity_on_hand,
                'reserved' => $stock->quantity_reserved
            ];
        }
        return [
            'product_id' => $productId,
            'total_available' => $totalAvailable,
            'warehouses' => $warehouses,
            'warehouse_specific' => false
        ];
    }

    /**
     * Process expired reservations
     */
    public function processExpiredReservations()
    {
        $expiredReservations = $this->reservationRepository
        ->getExpiredReservations();
        $processed = 0;

        foreach ($expiredReservations as $reservation) {
            try {
                $this->releaseReservation($reservation->id);
                $processed++;
            } catch (\Exception $e) {
                // Log error but continue processing
                \Log::error("Failed to release expired reservation {$reservation->id}: " . $e->getMessage());
            }
        }
        return $processed;
    }
}
