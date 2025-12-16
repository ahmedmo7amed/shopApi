<?php

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

class ProductOption extends Model
{

    protected $fillable =['name'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
