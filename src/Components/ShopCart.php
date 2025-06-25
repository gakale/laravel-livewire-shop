<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use LaravelLivewireShop\LaravelLivewireShop\Facades\Cart;
use LaravelLivewireShop\LaravelLivewireShop\Components\Traits\FindsAlternativeViews;

class ShopCart extends Component
{
    use FindsAlternativeViews;
    
    protected $listeners = [
        'cartUpdated' => '$refresh'
    ];

    public function render()
    {
        $cart = Cart::getCart();
        $total = Cart::getTotal();
        
        $viewPath = $this->findViewPath('shop-cart');
        
        return view($viewPath, [
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
