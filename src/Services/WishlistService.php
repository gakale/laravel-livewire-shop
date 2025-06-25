<?php

namespace VotreNamespace\LaravelLivewireShop\Services;

use VotreNamespace\LaravelLivewireShop\Models\Wishlist;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class WishlistService
{
    protected function getIdentifier()
    {
        return auth()->check() 
            ? ['user_id' => auth()->id()]
            : ['session_id' => session()->getId()];
    }

    public function add($productId)
    {
        $product = Product::findOrFail($productId);
        $identifier = $this->getIdentifier();
        
        $wishlist = Wishlist::where('product_id', $productId)
            ->where($identifier)
            ->first();
            
        if (!$wishlist) {
            Wishlist::create(array_merge($identifier, ['product_id' => $productId]));
            return true;
        }
        
        return false; // Déjà dans la wishlist
    }

    public function remove($productId)
    {
        $identifier = $this->getIdentifier();
        
        return Wishlist::where('product_id', $productId)
            ->where($identifier)
            ->delete();
    }

    public function toggle($productId)
    {
        if ($this->isInWishlist($productId)) {
            $this->remove($productId);
            return false;
        } else {
            $this->add($productId);
            return true;
        }
    }

    public function isInWishlist($productId)
    {
        $identifier = $this->getIdentifier();
        
        return Wishlist::where('product_id', $productId)
            ->where($identifier)
            ->exists();
    }

    public function getWishlist()
    {
        $identifier = $this->getIdentifier();
        
        return Wishlist::with('product')
            ->where($identifier)
            ->get();
    }

    public function getCount()
    {
        $identifier = $this->getIdentifier();
        
        return Wishlist::where($identifier)->count();
    }

    public function clear()
    {
        $identifier = $this->getIdentifier();
        
        return Wishlist::where($identifier)->delete();
    }
}
