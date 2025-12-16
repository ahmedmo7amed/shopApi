<?php

namespace App\modules\Inventory\Repositories;
use App\Modules\Inventory\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class StockTransactionRepository
{
    protected $stockTransaction;

    public function __construct(StockTransaction $stockTransaction)
    {
        $this->stockTransaction = $stockTransaction;
    }

    /**
     * Create a stock transaction record
     */
    public function createTransaction($productId, $warehouseId, $quantity, $type, $referenceId = null)
    {
        return $this->stockTransaction->create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'quantity' => $quantity,
            'type' => $type,
            'reference_id' => $referenceId
        ]);
    }
}
