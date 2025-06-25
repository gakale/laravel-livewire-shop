<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use LaravelLivewireShop\LaravelLivewireShop\Facades\Cart;

class CartIcon extends Component
{
    public $count = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCartCount'
    ];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function render()
    {
        return view('livewire-shop::components.cart-icon');
    }

    public function updateCartCount()
    {
        $this->count = Cart::getItemCount();
    }
}
