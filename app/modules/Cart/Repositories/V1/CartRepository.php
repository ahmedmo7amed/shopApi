<?php

namespace Modules\Invoice\Repositories\V1;
use Modules\Cart\Models\Cart;
use Modules\Cart\Repositories\V1\CartRepositoryInterface;

class CartRepository 
{
    public function getUserCart($userId)
    {
        
        return Cart::with('product')->where('user_id', $userId)->get();
    }

    public function addOrUpdate($userId, $productId, $quantity)
    {
        return Cart::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['quantity' => $quantity]
        );
    }

    public function updateQuantity($userId, $productId, $quantity)
    {
        return Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);
    }

    public function removeItem($userId, $productId)
    {
        return Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearCart($userId)
    {
        return Cart::where('user_id', $userId)->delete();
    }
}