<?php

namespace App\modules\Inventory\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ReserveStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'order_id' => 'nullable|integer|exists:orders,id',
            'expires_in' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product ID is required.',
            'product_id.integer' => 'Product ID must be an integer.',
            'product_id.exists' => 'The specified product does not exist.',
            'warehouse_id.required' => 'Warehouse ID is required.',
            'warehouse_id.integer' => 'Warehouse ID must be an integer.',
            'warehouse_id.exists' => 'The specified warehouse does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity must be at least 1.',
            'order_id.integer' => 'Order ID must be an integer.',
            'order_id.exists' => 'The specified order does not exist.',
            'expires_in.integer' => 'Expiration time must be an integer.',
            'expires_in.min' => 'Expiration time must be at least 1 minute.',
        ];
    }
}
