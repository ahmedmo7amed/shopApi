<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductQuote extends Pivot
{
    protected $casts = [
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];
}
