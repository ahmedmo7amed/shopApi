<?php

namespace Modules\Cart\Services\V1;

use Modules\Invoice\Repositories\V1\CartRepository;
use Modules\Invoice\Resources\V1\CartResource;

class CartServices
{
    protected CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

     public function getCart($userId)
    {
        $items = $this->cartRepository->getUserCart($userId);
        $total = $items->sum(fn ($item) =>
        optional($item->product)->price * $item->quantity);

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function addItem($userId, $productId, $quantity)
    {
        return $this->cartRepository
        ->addOrUpdate($userId, $productId, $quantity);
    }

    public function updateItem($userId, $productId, $quantity)
    {
        return $this->cartRepository
        ->updateQuantity($userId, $productId, $quantity);
    }

    public function removeItem($userId, $productId)
    {
        return $this->cartRepository
        ->removeItem($userId, $productId);
    }

    public function clearCart($userId)
    {
        return $this->cartRepository->clearCart($userId);
    }
}
