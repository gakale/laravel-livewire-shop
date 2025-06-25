<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use LaravelLivewireShop\LaravelLivewireShop\Services\WishlistService;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;
use LaravelLivewireShop\LaravelLivewireShop\Components\Traits\FindsAlternativeViews;

class AddToWishlist extends Component
{
    use FindsAlternativeViews;
    
    public $productId;
    public $inWishlist = false;
    
    // Définition explicite des listeners pour meilleure compatibilité
    protected $listeners = [
        'wishlistUpdated' => 'checkWishlistStatus'
    ];
    
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
        
        // Support de Livewire v2 et v3
        if (method_exists($this, 'dispatch')) {
            // Livewire v3
            $this->dispatch('wishlistUpdated');
        } else {
            // Livewire v2
            $this->emit('wishlistUpdated');
        }
        
        if ($this->inWishlist) {
            session()->flash('wishlist-message', 'Produit ajouté à vos favoris!');
        } else {
            session()->flash('wishlist-message', 'Produit retiré de vos favoris.');
        }
    }
    
    public function render()
    {
        $viewPath = $this->findViewPath('add-to-wishlist');
        
        // Passage explicite des variables à la vue pour s'assurer qu'elles sont disponibles
        return view($viewPath, [
            'inWishlist' => $this->inWishlist,
            'productId' => $this->productId
        ]);
    }
}
