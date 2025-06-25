<?php

namespace VotreNamespace\LaravelLivewireShop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class ShopServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/livewire-shop.php', 'livewire-shop');
        
        // Register services
        $this->app->singleton('cart', function ($app) {
            return new Services\CartService();
        });
        
        $this->app->singleton('wishlist', function ($app) {
            return new Services\WishlistService();
        });
        
        $this->app->singleton('coupon', function ($app) {
            return new Services\CouponService();
        });
    }

    public function boot()
    {
        // Publier les assets
        $this->publishes([
            __DIR__.'/../config/livewire-shop.php' => config_path('livewire-shop.php'),
        ], 'livewire-shop-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-shop'),
        ], 'livewire-shop-views');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'livewire-shop-migrations');

        // Charger les vues
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-shop');

        // Charger les migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Charger les routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Enregistrer les composants Livewire
        Livewire::component('shop-cart', Components\ShopCart::class);
        Livewire::component('add-to-cart', Components\AddToCart::class);
        Livewire::component('cart-icon', Components\CartIcon::class);
        Livewire::component('checkout-summary', Components\CheckoutSummary::class);
        
        // Nouveaux composants
        Livewire::component('coupon-code', Components\CouponCode::class);
        Livewire::component('wishlist-icon', Components\WishlistIcon::class);
        Livewire::component('add-to-wishlist', Components\AddToWishlist::class);
        Livewire::component('product-reviews', Components\ProductReviews::class);
        Livewire::component('product-search', Components\ProductSearch::class);

        // Commandes artisan personnalisées
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InstallShopCommand::class,
                Console\Commands\CreateProductCommand::class,
            ]);
        }
    }
}
