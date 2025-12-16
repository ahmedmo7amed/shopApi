<?php

namespace Modules\Cart\Http\Controllers\V1;

use App\Filament\Resources\CartResource;
use Illuminate\Routing\Controller;
use App\Models\Cart;
use Filament\Resources\Resource;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CartController extends Controller
{
    protected $cartServices;
    public function __construct(\Modules\Cart\Services\V1\CartServices $cartServices)
    {
        $this->middleware('auth');
        $this->cartServices = $cartServices;
    }

    public function index()
    {
    //     if (!Auth::check()) {
    //         return redirect()->route('login');
    //     }
    //     $cartItems = Auth::user()->cartItems()->with('product')->get();
    //     $total = $cartItems->sum(function ($item) {
    //         return optional($item->product)->price * $item->quantity;
    //     });
    //     //dd($cartItems);

    //     return CartResource::collection($cartItems)->additional(['meta' => ['total' => $total]]);
    //
        $cart = $this->cartServices->getCart(Auth::id());
        return response()->json(CartResource::collection($cart['items'])
            ->additional(['meta' => ['total' => $cart['total']]])
            , 200);
    }

    public function add(Request $request, Product $product)
    {
        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $this->cartServices->addItem(Auth::id(),
        $product->id, $validated['quantity']);
        return response()->json([
            'message' => 'Item added to cart successfully.'
        ], 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartServices->updateItem(Auth::id(), $product->id, $validated['quantity']);
        return response()->json(['message' => 'Cart item updated successfully.']);
    }

    public function remove(Product $product)
    {
        $this->cartServices->removeItem(Auth::id(), $product->id);
        return response()->json([
            'message' => 'Item removed from cart successfully.'
        ], 200);
    }

    public function clear()
    {
        $this->cartServices->clearCart(Auth::id());
        return response()->json(
            ['message' => 'Cart cleared successfully.'], 200);
    }
}
