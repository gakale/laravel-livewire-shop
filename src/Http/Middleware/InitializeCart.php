<?php

namespace VotreNamespace\LaravelLivewireShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InitializeCart
{
    public function handle(Request $request, Closure $next)
    {
        // Initialize cart in session if needed
        if (!session()->has(config('livewire-shop.cart.session_key'))) {
            session([config('livewire-shop.cart.session_key') => []]);
        }
        
        // Initialize wishlist in session if needed
        if (!session()->has(config('livewire-shop.wishlist.session_key'))) {
            session([config('livewire-shop.wishlist.session_key') => []]);
        }
        
        // Initialize coupon in session if needed
        if (!session()->has(config('livewire-shop.coupons.session_key'))) {
            session([config('livewire-shop.coupons.session_key') => null]);
        }
        
        // Add a unique session ID for guests to track cart/wishlist
        if (!session()->has('shop_session_id')) {
            session(['shop_session_id' => uniqid()]);
        }

        return $next($request);
    }
}
