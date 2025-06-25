<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Http\Controllers;

use Illuminate\Http\Request;
use LaravelLivewireShop\LaravelLivewireShop\Services\WishlistService;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    /**
     * Display the wishlist page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wishlist = $this->wishlistService->getWishlist();
        
        return view('livewire-shop::wishlist', [
            'wishlist' => $wishlist
        ]);
    }

    /**
     * Clear all items from wishlist
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $this->wishlistService->clear();
        
        return redirect()->route('livewire-shop.wishlist')
            ->with('success', 'Votre liste de souhaits a été vidée.');
    }
}
