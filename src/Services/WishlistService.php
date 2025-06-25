<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Services;

use LaravelLivewireShop\LaravelLivewireShop\Models\Wishlist;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;

class WishlistService
{
    /**
     * Session ID ou User ID pour identification
     */
    protected $identifier = [];
    
    /**
     * Constructeur qui initialise l'identifiant
     */
    public function __construct()
    {
        $this->identifier = $this->getIdentifier();
    }
    
    /**
     * Récupère l'identifiant de l'utilisateur actuel (auth ou session)
     */
    protected function getIdentifier()
    {
        return auth()->check() 
            ? ['user_id' => auth()->id()]
            : ['session_id' => session()->getId()];
    }

    public function add($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            
            $wishlist = Wishlist::where('product_id', $productId)
                ->where($this->identifier)
                ->first();
                
            if (!$wishlist) {
                Wishlist::create(array_merge($this->identifier, ['product_id' => $productId]));
                
                // Invalider le cache
                cache()->forget('wishlist_item_' . md5(json_encode($this->identifier) . '_' . $productId));
                cache()->forget('wishlist_count_' . md5(json_encode($this->identifier)));
                
                return true;
            }
            
            return false; // Déjà dans la wishlist
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function remove($productId)
    {
        try {
            $result = Wishlist::where('product_id', $productId)
                ->where($this->identifier)
                ->delete();
                
            // Invalider le cache
            cache()->forget('wishlist_item_' . md5(json_encode($this->identifier) . '_' . $productId));
            cache()->forget('wishlist_count_' . md5(json_encode($this->identifier)));
            
            return $result;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
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
        try {
            // Utiliser une requête cachée pour optimiser les performances
            return cache()->remember(
                'wishlist_item_' . md5(json_encode($this->identifier) . '_' . $productId),
                now()->addMinutes(30),
                function() use ($productId) {
                    return Wishlist::where('product_id', $productId)
                        ->where($this->identifier)
                        ->exists();
                }
            );
        } catch (\Exception $e) {
            // En cas d'erreur, retourner false plutôt que de faire planter l'application
            report($e); // Enregistre l'erreur dans les logs
            return false;
        }
    }

    public function getWishlist()
    {
        try {
            return Wishlist::with('product')
                ->where($this->identifier)
                ->get();
        } catch (\Exception $e) {
            report($e);
            return collect(); // Retourne une collection vide en cas d'erreur
        }
    }

    public function getCount()
    {
        try {
            // Utiliser une requête cachée pour optimiser les performances
            return cache()->remember(
                'wishlist_count_' . md5(json_encode($this->identifier)),
                now()->addMinutes(30),
                function() {
                    return Wishlist::where($this->identifier)->count();
                }
            );
        } catch (\Exception $e) {
            report($e);
            return 0;
        }
    }

    public function clear()
    {
        try {
            $result = Wishlist::where($this->identifier)->delete();
            
            // Supprimer tous les caches liés à cet identifiant
            cache()->forget('wishlist_count_' . md5(json_encode($this->identifier)));
            
            return $result;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
