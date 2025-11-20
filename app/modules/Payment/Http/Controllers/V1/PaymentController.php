<?php

namespace Modules\Payment\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function details()
    {
        $payments = auth()->user()->payments()
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('payment-details', compact('payments'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Make sure the user can only pay for their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Process payment logic here
        // This is just a placeholder - you'll need to integrate with your payment gateway
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'completed', // This should be updated based on payment gateway response
        ]);

        $order->update(['status' => 'paid']);

        return redirect()->route('order-history')
            ->with('success', 'Payment processed successfully');
    }
}
