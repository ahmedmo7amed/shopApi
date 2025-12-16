<?php

 // app/Modules/Inventory/Repositories/StockRepository.php
namespace App\Modules\Inventory\Repositories;

use App\Modules\Inventory\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockRepository
{
    protected $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Find stock with lock for update
     */
    public function findForUpdate($productId, $warehouseId)
    {
        return $this->stock
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->lockForUpdate()
            ->first();
    }

    /**
     * Get or create stock record
     */
    public function getOrCreateStock($productId, $warehouseId)
    {
        $stock = $this->stock
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if (!$stock) {
            $stock = $this->stock->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'quantity_on_hand' => 0,
                'quantity_reserved' => 0,
                'version' => 1
            ]);
        }

        return $stock;
    }

    /**
     * Update stock quantities
     */
    public function updateQuantities($productId, $warehouseId, $onHandDelta = 0, $reservedDelta = 0)
    {
        $stock = $this->findForUpdate($productId, $warehouseId);

        if (!$stock) {
            return false;
        }

        $stock->quantity_on_hand += $onHandDelta;
        $stock->quantity_reserved += $reservedDelta;
        $stock->version += 1;

        return $stock->save();
    }

    /**
     * Get available stock
     */
    public function getAvailableStock($productId, $warehouseId)
    {
        $stock = $this->stock
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        return $stock ? $stock->available : 0;
    }

    /**
     * Get stock by product
     */
    public function getStockByProduct($productId)
    {
        return $this->stock
            ->with('warehouse')
            ->where('product_id', $productId)
            ->get();
    }
}
