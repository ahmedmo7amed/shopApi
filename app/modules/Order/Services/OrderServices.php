<?php

namespace Modules\Order\Models;

use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Resources\V1\OrderResources;

class OrderServices
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    public function getAllOrders()
    {
        $order = $this->orderRepository->getAllOrders();
        return OrderResources::collection($order);
    }
    public function getOrderById($id)
    {
        $order = $this->orderRepository->getOrderById($id);
        return new OrderResources($order);
    }
    public function createOrder($data)
    {
        $order = $this->orderRepository->createOrder($data);
        return new OrderResources($order);
    }
    public function updateOrder($id, $data)
    {
        $order = $this->orderRepository->updateOrder($id, $data);
        return new OrderResources($order);
    }
    public function deleteOrder($id)
    {
        $this->orderRepository->deleteOrder($id);
    }

    public function getUserOrders($userId)
    {
        return $this->orderRepository->getOrdersByUserId($userId);
    }
    public function cancelOrder($id)
    {
        return $this->orderRepository->cancelOrder($id);
    }
    public function updateStatus($id, $data)
    {
        return $this->orderRepository
        ->updateStatus($id, $data['status']);
    }
    public function applyDiscount($id, $data)
    {
        $order = $this->orderRepository->getOrderById($id);
        if (isset($data['discount_percentage'])) {
            $discountAmount = $order->total_amount * ($data['discount_percentage'] / 100);
            $order->total_amount -= $discountAmount;
        } elseif (isset($data['discount_amount'])) {
            $order->total_amount -= $data['discount_amount'];
        }
        $order->save();
        return new OrderResources($order);
    }
    public function getOrdersForInvoiceGeneration()
    {
        return $this->orderRepository->getAllOrders()
            ->filter(function ($order) {
                return $order->status === 'completed'
                && !$order->invoice_generated;
            });
    }
    public function generateInvoiceData($orderId)
    {
        $order = $this->orderRepository->getOrderById($orderId);

        // Prepare invoice data
        $invoiceData = [
            'order_id' => $order->id,
            'customer_name' => $order->user->name,
            'items' => $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'total_amount' => $order->total_amount,
            'order_date' => $order->created_at->toDateString(),
        ];

        return $invoiceData;
    }
    public function reorder(Order $order,int $userId){
        $newOrderData = $order->replicate()->toArray();
        $newOrderData['user_id'] = $userId;
        unset($newOrderData['id']);
        $newOrder = $this->orderRepository->createOrder($newOrderData);

        foreach ($order->items as $item) {
            $newItemData = $item->replicate()->toArray();
            $newItemData['order_id'] = $newOrder->id;
            unset($newItemData['id']);
            $newOrder->items()->create($newItemData);
        }

        return new OrderResources($newOrder);
    }
}
