<?php

use App\Http\Controllers\ProductImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Filament\Resources\ContactUsResource\Pages\ContactUsForm;
use Illuminate\Support\Facades\Session;
use Filament\Facades\Filament;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\QuoteController;
use App\Models\Quote;
use Illuminate\Support\Facades\Artisan;

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/resend/verfication/email', [CustomerAuthController::class, 'resendVerification'])->name('resend.email');
Route::get('/verify', [\App\Http\Controllers\CustomerAuthController::class, 'verify'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('/landing', function () {
    return view('pages.landing-page');
});
//Route::get('/home', [\App\Http\Controllers\HomeController::class, 'home']);
Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::get('whatsapp/send', function () {
    return redirect()->away('https://wa.me/966554289000');
})->name('whatsapp');

Route::middleware(['auth'])->group(function () {
   
    Route::resource('cart', CartController::class);
});

    Route::Post('/contact-us', [\App\Http\Controllers\ContactUSController::class,'contactUSPost'])->name('contact-us');
    ////////////////////////////////////////////////////////////////////
    Route::get('/quotes/{quote}/download', function (Quote $quote) {
        return response()->download(
            storage_path("app/public/quotes/quote-{$quote->id}.pdf")
        );
    })->name('quotes.download');
    //////////////////////////////////////////////////////////////////////

    Route::get('lang/{locale}', function ($locale) {
        if (!in_array($locale, ['en', 'de', 'es', 'fr', 'pt', 'cn', 'ae'])) {
            abort(400);
        }
        Session()->put('locale', $locale);
        Session::get('locale');
        return redirect()->back();
    })->name('lang');

  
        //Fortify::registerRoutes();  // This registers all the Fortify authentication routes, including register

    Route::prefix('authentication')->group(function () {
        Route::get('login',[CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login',[CustomerAuthController::class, 'login'])->name('login');
        Route::post('register',[CustomerAuthController::class, 'register'])->name('register');
         Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
        //Route::post('register', [CustomerAuthController::class, 'register'])->name('register');
       });



    Route::get('/clear-cache', function () {
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return "Cache is cleared";
    })->name('clear.cache');


    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    //Route::resource('quotes', QuoteController::class);

    // Additional product routes if needed
    Route::get('products/category/{category}', [ProductController::class, 'category'])->name('products.by.category');
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::prefix('products')->group(function () {
        Route::post('{product}/images', [ProductImageController::class, 'store'])->name('product.images.store');
        Route::delete('{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('product.images.destroy');
        Route::post('{product}/images/reorder', [ProductImageController::class, 'reorder'])->name('product.images.reorder');
        Route::put('{product}/images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('product.images.setPrimary');
    });
   Route::prefix('products')->group(function () {
        Route::get('/category/{categoryId}', [ProductController::class, 'byCategory'])->name('products.byCategory');
    });

    // Cart Routes
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');


    // Wishlist Routes
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // Order Routes
    Route::get('/order-history', [OrderController::class, 'history'])->name('order-history');
    Route::get('/invoice/{order}', [OrderController::class, 'invoice'])->name('invoice-template');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Payment Routes
    Route::get('/payment-details', [PaymentController::class, 'details'])->name('payment-details');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

    // Product List Routes
    Route::get('/products/list', [ProductController::class, 'list'])->name('list-products');
    //Route::get('/products/page/{product}', [ProductController::class, 'page'])->name('product-page');
    Route::get('/products/wishlist', [WishlistController::class, 'index'])->name('list-wish');
    //Route::get('/dashboard-02', [DashboardController::class, 'index'])->name('dashboard-02');
    // Pricing Route
    Route::get('/pricing', function () {
        return view('pricing');
    })->name('pricing');
    //require __DIR__.'/auth.php';
