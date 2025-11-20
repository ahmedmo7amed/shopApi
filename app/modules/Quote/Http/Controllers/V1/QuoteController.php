<?php

namespace Modules\Quote\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Mail\QuoteGenerated;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    public function create()
    {
        return view('quotes.create');
    }
    public function sendQuote($quoteId)
    {
        $quote = Quote::with('products')->findOrFail($quoteId);
        if ($quote) {
            Mail::to($quote->customer_email)->send(new QuoteGenerated($quote));
        }
//        $quote->update(['status' => 'sent']);
        return redirect()->route('quotes.index')->with('success', 'Quote sent successfully!');

    }

    public function store(Request $request)
    {
        // حفظ البيانات في قاعدة البيانات
        $quote = Quote::create($request->only([
            'company_name', 'contact_name', 'email', 'phone', 'tax_number', 'address',
            'subtotal', 'tax_total', 'grand_total', 'expiration_date', 'special_notes', 'payment_terms'
        ]));

        // حفظ المنتجات المرتبطة
        foreach ($request->products as $product) {
            $quote->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'tax_rate' => $product['tax_rate'],
            ]);
        }

        Mail::to('admin@example.com')->send(new QuoteGenerated($quote));
        return redirect()->route('quotes.index')->with('success', 'Quote created successfully!');
    }
}
