<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Modules\Product\Models\Product;
use App\Models\Review;
use Modules\Order\Models\Order;
use App\Models\Cart;



class User extends Authenticatable implements FilamentUser , MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,  HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function hasPurchased($product)
    {
        // Assuming you have an orders table and a pivot table like order_product
        return $this->orders()
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
    }

    public function orders()
    {
        return $this->hasMany(Order::class); // Replace Order with your actual model name
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

//    public function wishlists()
//    {
//        return $this->hasMany(Wishlist::class);
//    }
    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    public function isInWishlist($userId)
    {
        return auth()->user()->wishlists()->where('user_id', $userId)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }
    public function canAccessFilament(): bool

    {
        return $this->hasRole('Admin');
    }
     //Role relationship


}
