<?php 

namespace Modules\Order\Models;

use Modules\Order\Repositories\OrderRepository;

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
        return OrderResource::collection($order);
    }
    public function getOrderById($id)
    {
        $order = $this->orderRepository->getOrderById($id);
        return new OrderResource($order);
    }
    public function createOrder($data)
    {
        $order = $this->orderRepository->createOrder($data);
        return new OrderResource($order);
    }
    public function updateOrder($id, $data)
    {
        $order = $this->orderRepository->updateOrder($id, $data);
        return new OrderResource($order);
    }
    public function deleteOrder($id)
    {
        $this->orderRepository->deleteOrder($id);
    }

}