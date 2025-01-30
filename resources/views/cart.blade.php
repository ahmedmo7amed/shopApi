@extends('layouts.app')
@section('title', 'Cart')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Cart</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Ecommerce</li>
    <li class="breadcrumb-item active">Cart</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Cart</h5>
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
                            <div class="order-history table-responsive wishlist">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <img class="img-fluid img-40" src="{{ asset( 'storage/' .$item->product->images[0]) }}" alt="{{ $item->product->name }}">
                                            </td>
                                            <td>
                                                <div class="product-name">
                                                    <a href="{{ route('products.show', $item->product) }}">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->product->price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item->product) }}" method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">Update</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item->product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i data-feather="x-circle"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <div class="input-group">
                                                <input class="form-control me-2" type="text" placeholder="Enter coupon code">
                                                <a class="btn btn-primary" href="#">Apply</a>
                                            </div>
                                        </td>
                                        <td class="total-amount">
                                            <h6 class="m-0 text-end"><span class="f-w-600">Total Price :</span></h6>
                                        </td>
                                        <td><span>${{ number_format($total, 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-end" colspan="5">
                                            <a class="btn btn-secondary cart-btn-transform" href="{{ route('products.index') }}">Continue Shopping</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-success cart-btn-transform" href="{{ route('checkout') }}">Check Out</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/touchspin/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/input-groups.min.js') }}"></script>
@endsection
