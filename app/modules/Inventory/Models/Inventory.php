<?php

namespace App\Modules\Inventory\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'note',
    ];

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservations', 'product_id', 'warehouse_id', 'product_id', 'warehouse_id')
                    ->withPivot('quantity', 'order_id', 'expires_at', 'status')
                    ->withTimestamps();
    }
}
