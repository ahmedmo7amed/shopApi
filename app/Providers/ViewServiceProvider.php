<?php
namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $cartCount = auth()->user()->cartItems()->count();
            } else {
                $cartCount = 0;
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
