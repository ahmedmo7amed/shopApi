<?php

namespace App\Modules\Inventory\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservations';

    protected $fillable = [
        'id',
        'product_id',
        'warehouse_id',
        'quantity',
        'order_id',
        'expires_at',
        'status',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'expires_at' => 'datetime',
    ];

}
