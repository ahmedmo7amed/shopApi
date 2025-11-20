<?php

namespace App\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // استبدل جميع التوابع بالكود التالي:
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist;
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Request $request, Product $product)
    {
        auth()->user()->wishlist()->attach($product->id);
        return back()->with('success', 'Product added!');
    }

    public function destroy(Product $product)
    {
        auth()->user()->wishlist()->detach($product->id);
        return back()->with('success', 'Product removed!');
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();

       
        $user->wishlist()->toggle($product->id);

        return back()->with('success', 'Product toggled in wishlist!');
    }

//    public function toggle(Product $product)
//    {
//        $user = auth()->user();
//
//        // Check if product exists in wishlist
//        if($user->wishlist()->where('product_id', $product->id)->exists()) {
//            // Remove from wishlist
//            $user->wishlist()->where('product_id', $product->id)->delete();
//            return back()->with('success', 'Tank removed from favorites!');
//        }
//
//        // Add to wishlist
//        Wishlist::create([
//            'user_id' => $user->id,
//            'product_id' => $product->id
//        ]);
//
//        return back()->with('success', 'Tank added to favorites!');
//    }


//    public function isInWishlist($productId)
//    {
//        return auth()->user()->wishlists()->where('product_id', $productId)->exists();
//    }

//    public function moveToCart(Wishlist $wishlist)
//    {
//        auth()->user()->cartItems()->create([
//            'product_id' => $wishlist->product_id,
//            'quantity' => 1,
//        ]);
//        $wishlist->delete();
//        return back()->with('success', 'Item moved to cart successfully.');
//    }


}
