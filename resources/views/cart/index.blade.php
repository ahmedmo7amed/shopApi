@extends('layouts.app')

@section('title', 'Shopping Cart')


@section('css')
    .quantity-input {
    width: 80px;
    }
    .product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    }
@endsection



@section('breadcrumb-title')
<h3>Shopping Cart</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Ecommerce</li>
<li class="breadcrumb-item active">Shopping Cart</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Cart Items</h5>

                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($cartItems->isEmpty())
                        <div class="text-center py-5">
                            <i data-feather="shopping-cart" style="width: 48px; height: 48px;" class="text-muted mb-3"></i>
                            <h4>Your cart is empty</h4>
                            <p class="text-muted">Add items to your cart to proceed with checkout.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                Continue Shopping
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($item) && $item !== null && isset($item->product))
                                    @dd($item->product);
                                @else
                                    echo 'Item or product not defined.';
                                @endif

                                    @foreach($cartItems as $item)


                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                @if($item->product->image)
                                                        {
                                                        <img src="{{ asset( $item->product->image[0]) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="product-image me-3">
                                                    @else
                                                        <img src="{{ asset('assets/images/product/default.jpg') }}"
                                                             alt="Default Product Image"
                                                             class="product-image me-3">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('products.show', $item->product) }}" class="text-dark">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h6>
                                                        @if($item->product->stock_status === 'out_of_stock')
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @elseif($item->product->stock_status === 'low_stock')
                                                            <span class="badge bg-warning">Low Stock</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->product->price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item->product) }}"
                                                      method="POST"
                                                      class="d-flex align-items-center">
                                                    @csrf
                                                    <input type="number"
                                                           name="quantity"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           class="form-control quantity-input me-2">
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-primary">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item->product) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td colspan="2"><strong>${{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    Clear Cart
                                </button>
                            </form>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                    Continue Shopping
                                </a>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                    Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
