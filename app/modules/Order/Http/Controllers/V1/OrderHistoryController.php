<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use  Modules\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Models\OrderHistory;

class OrderHistoryController extends Controller
{
    public function index(Order $order)
    {
        return view('order-histories.index', [
            'histories' => $order->histories()->latest()->paginate(10),
            'order' => $order,
        ]);
    }

    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $order->histories()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Order history added successfully.');
    }
}
