<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use LaravelLivewireShop\LaravelLivewireShop\Services\WishlistService;

class WishlistIcon extends Component
{
    public $count = 0;
    
    protected $listeners = [
        'wishlistUpdated' => 'updateCount'
    ];

    public function mount(WishlistService $wishlistService)
    {
        $this->count = $wishlistService->getCount();
    }
    
    public function updateCount()
    {
        $this->count = app(WishlistService::class)->getCount();
    }
    
    public function render()
    {
        return view('livewire-shop::components.wishlist-icon');
    }
}
