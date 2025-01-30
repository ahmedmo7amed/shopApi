<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_date',
        'status',
        'shipping_address',
        'shipping_method',
        'shipping_cost',
        'subtotal',
        'tax',
        'total',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'shipping_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

//    public function customer()
//    {
//        return $this->belongsTo(Customer::class);
//    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }
    protected static function booted()
    {
        static::saving(function ($order) {
            $order->total = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        });
    }
}
