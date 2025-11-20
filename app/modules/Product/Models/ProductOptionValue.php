<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $fillable = ['value', 'price', 'length', 'diameter', 'height'];

    public function option()
    {
        return $this->belongsTo(ProductOption::class);
    }
}
