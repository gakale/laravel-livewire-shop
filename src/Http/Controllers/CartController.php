<?php

namespace VotreNamespace\LaravelLivewireShop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view('livewire-shop::pages.cart');
    }

    public function checkout()
    {
        $cart = app('cart')->getCart();
        
        if (empty($cart)) {
            return redirect()->route('shop.cart')->with('error', 'Votre panier est vide');
        }

        return view('livewire-shop::pages.checkout');
    }
}
