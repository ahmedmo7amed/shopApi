<?php
namespace Modules\Order\Repositories;
use Modules\Order\Models\Order;

class OrderRepository
    {

        protected  Order $orderModel;

        public function __construct(Order $orderModel)
        {
            $this->orderModel = $orderModel;
        }

        public function getAllOrders()
        {
            return $this->orderModel->all();
        }
        public function getOrderById($id)
        {

            return $this->orderModel->findOrFail($id);
        }
        public function createOrder($data)
        {
            return $this->orderModel->create($data);
        }
        public function updateOrder($id, $data)
        {
            return $this->orderModel->findOrFail($id)->update($data);
        }
        public function deleteOrder($id)
        {
            return $this->orderModel->findOrFail($id)->delete();
        }
        public function cancelOrder($id)
        {
            $order = $this->orderModel->findOrFail($id);
            $order->status = 'canceled';
            return $order->save();
        }
        public function updateStatus($id, $status)
        {
            $order = $this->orderModel->findOrFail($id);
            $order->status = $status;
            return $order->save();
        }
        public function getOrdersByUserId($userId)
        {
            return $this->orderModel->where('user_id', $userId)
                                    ->with(['items.product'])
                                    ->latest()
                                    ->paginate(10);
        }
    }
