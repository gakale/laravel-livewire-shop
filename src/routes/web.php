<?php

use Illuminate\Support\Facades\Route;
use VotreNamespace\LaravelLivewireShop\Http\Controllers\ShopController;
use VotreNamespace\LaravelLivewireShop\Http\Controllers\CartController;
use VotreNamespace\LaravelLivewireShop\Http\Controllers\WishlistController;
use VotreNamespace\LaravelLivewireShop\Http\Middleware\InitializeCart;

// Routes for shop
Route::prefix(config('livewire-shop.routes.shop_prefix', 'boutique'))
    ->middleware(['web', InitializeCart::class])
    ->group(function () {
        Route::get('/', [ShopController::class, 'index'])
            ->name('livewire-shop.index');
            
        Route::get('/produit/{product}', [ShopController::class, 'show'])
            ->name('livewire-shop.product');
            
        Route::get('/recherche', [ShopController::class, 'search'])
            ->name('livewire-shop.search');
    });

// Routes for cart
Route::prefix(config('livewire-shop.routes.cart_prefix', 'panier'))
    ->middleware(['web', InitializeCart::class])
    ->group(function () {
        Route::get('/', [CartController::class, 'index'])
            ->name('livewire-shop.cart');
            
        Route::get('/paiement', [CartController::class, 'checkout'])
            ->name('livewire-shop.checkout');
            
        Route::post('/paiement', [CartController::class, 'processCheckout'])
            ->name('livewire-shop.process-checkout');
    });
    
// Routes for wishlist
Route::prefix(config('livewire-shop.routes.wishlist_prefix', 'favoris'))
    ->middleware(['web', InitializeCart::class])
    ->group(function () {
        Route::get('/', [WishlistController::class, 'index'])
            ->name('livewire-shop.wishlist');
            
        Route::get('/vider', [WishlistController::class, 'clear'])
            ->name('livewire-shop.wishlist.clear');
    });
