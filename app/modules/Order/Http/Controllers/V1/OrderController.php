<?php

namespace Modules\Order\Http\Controllers\V1;

use App\Filament\Resources\OrderResource;
use App\Http\Controllers\Controller;
use Modules\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Models\OrderServices;

class OrderController extends Controller
{
    protected OrderServices $orderServices;
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }
    public function createOrder(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $this->orderServices->createOrder($data);

        return response()->json(['message' =>
        'Order created successfully'], 201);
    }
    public function getOrderById($id)
    {
        $order = $this->orderServices->getOrderById($id);
        return response()->json(new OrderResource($order), 200);
    }
    public function getAllOrders()
    {
        $orders = $this->orderServices->getAllOrders();
        return response()->json(OrderResource::collection($orders)
        , 200);
    }
    public function updateOrder($id, Request $request)
    {
        $data = $request->all();
        $this->orderServices->updateOrder($id, $data);
        return response()->json(['message' =>
        'Order updated successfully'], 200);
    }
    public function cancelOrder($id)
    {
        $this->orderServices->cancelOrder($id);
        return response()->json(['message' =>
        'Order canceled successfully'], 200);
    }
    public function updateStatus($id, Request $request){
        $data = $request->all();
        $this->orderServices->updateStatus($id, $data);
        return response()->json(['message' =>
        'Order status updated successfully'], 200);
    }
    public function applyDiscount($id, Request $request){
        $data = $request->all();
        $this->orderServices->applyDiscount($id, $data);
        return response()->json(['message' =>
        'Discount applied successfully'], 200);
    }
    
    public function history()
    {
        // $orders = Auth::user()->orders()
        //     ->with(['items.product'])
        //     ->latest()
        //     ->paginate(10);

        $orders = $this->orderServices->getUserOrders(Auth::id());
        return response()->json(OrderResource::collection($orders), 200);
    }

    public function invoice(Order $order)
    {
        // Make sure the user can only view their own orders
        // if ($order->user_id !== Auth::id()) {
        //     abort(403);
        // }
        // return view('invoice-template', compact('order'));

        $invoiceData = $this->orderServices
        ->generateInvoiceData($order->id);
        return response()->json($invoiceData, 200);
    }
    public function reorder(Order $order)
    {
        // Make sure the user can only reorder their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $this->orderServices->reorder($order->id, Auth::id());

        return response()->json(['message' =>
        'Items added to cart successfully'], 200);
    }

}
