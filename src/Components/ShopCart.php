<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use VotreNamespace\LaravelLivewireShop\Facades\Cart;

class ShopCart extends Component
{
    protected $listeners = [
        'cartUpdated' => '$refresh'
    ];

    public function render()
    {
        $cart = Cart::getCart();
        $total = Cart::getTotal();
        
        return view('livewire-shop::components.shop-cart', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function removeItem($itemId)
    {
        Cart::remove($itemId);
        $this->dispatch('cartUpdated');
    }

    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) {
            $quantity = 1;
        }
        
        Cart::update($itemId, $quantity);
        $this->dispatch('cartUpdated');
    }

    public function clearCart()
    {
        Cart::clear();
        $this->dispatch('cartUpdated');
    }
}
