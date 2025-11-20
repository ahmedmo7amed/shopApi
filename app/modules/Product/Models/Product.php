<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Quote;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Review;
use Modules\Category\Models\Category;



class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'barcode',
        'price',
        'cost',
        'quantity',
        'category_id',
        'status',
        'stock_status',
        'slug',
        'images' ,
        'is_best_selling',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'quantity' => 'integer',
        'status' => 'boolean',
        'images' => 'array',
    ];

    protected $appends = ['stock_status'];

    public function quotes()
    {
        return $this->belongsToMany(Quote::class)
            ->withPivot('quantity', 'unit_price', 'tax_rate');
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Accessors
    public function getPrimaryImageAttribute()
    {
        return $this->images()->first()?->image_path ?? null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

//    public function isInWishlist($userId)
//    {
//        return $this->wishlists()->where('user_id', $userId)->exists();
//    }
    public function isInWishlist($userId = null)
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            return false; // User not authenticated
        }

        return $this->wishlists()->where('user_id', $userId)->exists();
    }



    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Product $product){
            foreach ($product->images as $image){
                Storage::delete("public/$image");
            }
        });

        static::updating(function (Product $product){
            $imagesToDelete = array_diff($product->getOriginal('images') , $product->images);
            foreach ($imagesToDelete as $image)
            {
                Storage::delete("public/$image");
            }

        });

        static::creating(function ($product) {
            if (empty($product->slug)) {

                $product->slug = Str::slug($product->name);
            }
        });

        static::saved(function ($product) {
            $tempImages = request()->get('temp_images', []);

            if (!empty($tempImages)) {
                // حذف الصور القديمة
                $product->images()->delete();

                // حفظ الصور الجديدة
                foreach ($tempImages as $image) {
                    $product->images()->create([
                        'image_path' => 'product-images/' . basename($image),
                    ]);
                }
            }
        });
//        static::creating(function ($product) {
//            if (empty($product->slug)) {
//                $product->slug = Str::slug($product->name);
//            }
//        });
    }


    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->quantity <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }
}
