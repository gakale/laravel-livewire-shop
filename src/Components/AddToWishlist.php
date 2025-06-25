<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use VotreNamespace\LaravelLivewireShop\Services\WishlistService;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class AddToWishlist extends Component
{
    public $productId;
    public $inWishlist = false;
    
    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkWishlistStatus();
    }
    
    public function checkWishlistStatus()
    {
        $wishlistService = app(WishlistService::class);
        $this->inWishlist = $wishlistService->isInWishlist($this->productId);
    }
    
    public function toggleWishlist()
    {
        $wishlistService = app(WishlistService::class);
        $this->inWishlist = $wishlistService->toggle($this->productId);
        
        $this->emit('wishlistUpdated');
        
        if ($this->inWishlist) {
            session()->flash('wishlist-message', 'Produit ajouté à vos favoris!');
        } else {
            session()->flash('wishlist-message', 'Produit retiré de vos favoris.');
        }
    }
    
    public function render()
    {
        return view('livewire-shop::components.add-to-wishlist');
    }
}
