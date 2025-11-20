<?php

namespace Modules\Order\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Modules\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('order-history', compact('orders'));
    }

    public function invoice(Order $order)
    {
        // Make sure the user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('invoice-template', compact('order'));
    }
}
