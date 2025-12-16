<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\Product;
use App\Models\Warehouse;

class StockTransaction extends Model
{
    protected $table = 'stock_transactions';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'amount',
        'reason',
        'metadata',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'integer',
        'metadata' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
