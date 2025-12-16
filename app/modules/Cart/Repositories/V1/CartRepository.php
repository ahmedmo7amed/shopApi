<?php

namespace Modules\Invoice\Repositories\V1;
use Modules\Cart\Models\Cart;

class CartRepository
{
    public function getUserCart($userId)
    {
        $data = Cart::with('product')
        ->where('user_id', $userId)->get();
        return response()->json(["cart_items" => $data,
        "message" => "User cart retrieved successfully"]);
    }

    public function addOrUpdate($userId, $productId, $quantity)
    {
        $data = ["item" => Cart::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId
            ],
            [
                'quantity' => $quantity
            ]
        ),
            "message" => "Item added/updated in cart" ,200];
        return response()->json($data);
    }

    public function updateQuantity($userId, $productId, $quantity)
    {
        $data = ["item" => Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]),
            "message" => "Cart item quantity updated" ,200];
        return response()->json($data);
    }

    public function removeItem($userId, $productId)
    {
        $data = ["item" => Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete(),"message" => "Item removed from cart" ,200];
        return response()->json($data);
    }

    public function clearCart($userId)
    {
        $data = ["item" => Cart::where('user_id', $userId)->delete(),
            "message" => "Cart cleared" ,200];
        return response()->json($data);
    }
}
