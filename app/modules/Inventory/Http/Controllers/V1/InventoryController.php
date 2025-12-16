<?php

namespace App\Modules\Inventory\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Services\InventoryService;
use App\Modules\Inventory\Resources\V1\StockResources;
use App\Modules\Inventory\Http\Requests\ReserveStockRequest;
use App\modules\Inventory\Resources\V1\ReservationResources;
use Illuminate\Http\Request;
use App\Modules\Inventory\Repositories\StockRepository;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Reserve stock
     */
    public function reserve(ReserveStockRequest $request)
    {
        try {
            $reservation = $this->inventoryService->reserveStock(
                $request->product_id,
                $request->warehouse_id,
                $request->quantity,
                $request->order_id,
                $request->expires_in ?? 10
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock reserved successfully',
                'data' => new ReservationResources($reservation)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Release reservation
     */
    public function releaseReservation($reservationId)
    {
        try {
            $released = $this->inventoryService
            ->releaseReservation($reservationId);

            if (!$released) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or already released'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reservation released successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Commit reservation (ship)
     */
    public function commitReservation($reservationId)
    {
        try {
            $committed = $this->inventoryService->commitReservation($reservationId);

            if (!$committed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found or already processed'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reservation committed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Add stock (inbound)
     */
    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255'
        ]);

        try {
            $stock = $this->inventoryService->addStock(
                $request->product_id,
                $request->warehouse_id,
                $request->quantity,
                $request->reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully',
                'data' => new StockResources($stock)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get stock availability
     */
    public function getAvailability(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id'
        ]);

        try {
            $availability = $this->inventoryService->getAvailability(
                $request->product_id,
                $request->warehouse_id
            );

            return response()->json([
                'success' => true,
                'data' => $availability
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get stock by product and warehouse
     */
    public function getStock($productId, $warehouseId)
    {
        try {
            $stockRepository = app(StockRepository::class);
            $stock = $stockRepository
            ->findForUpdate($productId, $warehouseId);

            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new StockResources($stock)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Process expired reservations (admin endpoint)
     */
    public function processExpired()
    {
        try {
            $processed = $this->inventoryService
            ->processExpiredReservations();

            return response()->json([
                'success' => true,
                'message' => "Processed {$processed} expired reservations"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

