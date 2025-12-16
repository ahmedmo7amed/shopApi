<?php

namespace Modules\Quote\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Product\Models\Product;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use  HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'tax_number',
        'expiration_date',
        'special_notes',
        'subtotal',
        'tax_total',
        'grand_total',

    ];

    protected $casts = [
        'expiration_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

//    public function products()
//    {
//        return $this->belongsToMany(Product::class)
//            ->using(ProductQuote::class)
//            ->withPivot(['quantity', 'unit_price', 'tax_rate']);
//    }
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'unit_price', 'tax_rate']);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($quote) {
            $quote->subtotal = $quote->products->sum(fn($p) => $p->pivot->quantity * $p->pivot->unit_price
            );

            $quote->tax_total = $quote->products->sum(fn($p) => ($p->pivot->quantity * $p->pivot->unit_price) * ($p->pivot->tax_rate / 100)
            );

            $quote->grand_total = $quote->subtotal + $quote->tax_total;
        });
    }


//  // In App\Models\Quote.php
//    public function products()
//    {
//        return $this->belongsToMany(Product::class, 'product_quote')
//            ->withPivot('quantity', 'unit_price', 'tax_rate')
//            ->withTimestamps();
//   }
//    public function products()
//    {
//        return $this->belongsToMany(Product::class,'product_quote')->withTimestamps();
//    }

// Keep these accessors for calculated fields
    public function getSubtotalAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity * $product->pivot->unit_price;
        });
    }

    public function getTaxTotalAttribute()
    {
        return $this->products->sum(function ($product) {
            return ($product->pivot->quantity * $product->pivot->unit_price) * ($product->pivot->tax_rate / 100);
        });
    }

    public function getGrandTotalAttribute()
    {
        return $this->subtotal + $this->tax_total;
    }

}
