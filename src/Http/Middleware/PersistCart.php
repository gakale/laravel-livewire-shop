<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PersistCart
{
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur se connecte, fusionner les paniers
        if (auth()->check() && session()->has('guest_cart')) {
            $this->mergeGuestCart();
        }

        return $next($request);
    }

    private function mergeGuestCart()
    {
        $guestCart = session('guest_cart', []);
        
        if (!empty($guestCart)) {
            foreach ($guestCart as $item) {
                app('cart')->add(
                    $item['product_id'], 
                    $item['quantity'], 
                    $item['attributes'] ?? []
                );
            }
            
            session()->forget('guest_cart');
        }
    }
}
