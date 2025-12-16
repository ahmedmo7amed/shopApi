<?php

namespace App\modules\Inventory\Models;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = ['product_id', 'warehouse_id'];
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity_on_hand',
        'quantity_reserved',
        'version',
        'note'
    ];
}
