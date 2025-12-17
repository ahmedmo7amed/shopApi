<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Modules\Product\Http\Controllers\V1\ProductController;
use Modules\Cart\Http\Controllers\V1\CartController;
use App\Http\Controllers\Api\V1\WishlistController;
use Modules\Order\Http\Controllers\V1\OrderController;
use App\Http\Controllers\Api\V1\CheckoutController;
use Modules\Payment\Http\Controllers\V1\PaymentController;
use Modules\Quote\Http\Controllers\V1\QuoteController;

// ✅ Categories
Route::apiResource('categories', CategoryController::class);

// API registration (mirror of web register)
Route::post('register', [RegisteredUserController::class, 'store']);

// ✅ Products
Route::get('products/search', [ProductController::class, 'search']);
Route::get('products/category/{id}', [ProductController::class, 'byCategory']);
Route::apiResource('products', ProductController::class);

// ✅ Cart
Route::middleware('auth:api')->group(function () {
    Route::post('cart/add/{product}', [CartController::class, 'add']);
    Route::post('cart/remove/{product}', [CartController::class, 'remove']);
    Route::get('cart', [CartController::class, 'index']);
});

// ✅ Wishlist
Route::middleware('auth:api')->group(function () {
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/toggle/{product}', [WishlistController::class, 'toggle']);
});

// ✅ Orders
Route::middleware('auth:api')->group(function () {
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::post('checkout', [CheckoutController::class, 'process']);
});

// ✅ Payment
Route::post('payment/process', [PaymentController::class, 'process']);

// ✅ Quotes
Route::apiResource('quotes', QuoteController::class);

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

//require __DIR__.'/auth.php';
require __DIR__.'/auth.php';
