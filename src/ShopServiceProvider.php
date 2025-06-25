<?php

namespace LaravelLivewireShop\LaravelLivewireShop;

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

    /**
     * Vérifie la compatibilité avec la version actuelle de Laravel
     *
     * @return bool
     */
    private function checkCompatibility()
    {
        $laravelVersion = app()->version();
        $supportedVersions = ['8.*', '9.*', '10.*', '11.*', '12.*'];
        
        foreach ($supportedVersions as $version) {
            if (version_compare($laravelVersion, str_replace('.*', '.0', $version), '>=') && 
                version_compare($laravelVersion, str_replace('.*', '.999', $version), '<=')) {
                return true;
            }
        }
        
        $this->app->booted(function () use ($laravelVersion, $supportedVersions) {
            $supportedVersionsString = implode(', ', $supportedVersions);
            throw new \Exception("Laravel Livewire Shop n'est pas compatible avec Laravel $laravelVersion. Versions supportées: $supportedVersionsString");
        });
        
        return false;
    }
    
    public function boot()
    {
        // Vérifier la compatibilité avec la version de Laravel
        $this->checkCompatibility();
        
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
        
        // Enregistrer les commandes
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\InstallShopCommand::class,
                Console\Commands\CreateProductCommand::class,
                Console\Commands\OptimizeShopCommand::class,
                Console\Commands\PublishShopViews::class,
                Console\Commands\FixComponentViews::class,
            ]);
        }

        // Charger les vues avec priorité aux vues publiées
        $this->loadViewsFrom(resource_path('views/vendor/livewire-shop'), 'livewire-shop');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-shop');

        // Charger les migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Charger les routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Enregistrer les composants Livewire avec support des vues publiées ou du vendor
        $this->registerLivewireComponents();

        // Les commandes artisan sont déjà enregistrées plus haut
    }
    
    /**
     * Enregistre les composants Livewire avec support des vues publiées
     *
     * @return void
     */
    protected function registerLivewireComponents()
    {
        // Liste des composants à enregistrer
        $components = [
            'shop-cart' => Components\ShopCart::class,
            'add-to-cart' => Components\AddToCart::class,
            'cart-icon' => Components\CartIcon::class,
            'checkout-summary' => Components\CheckoutSummary::class,
            'coupon-code' => Components\CouponCode::class,
            'wishlist-icon' => Components\WishlistIcon::class,
            'add-to-wishlist' => Components\AddToWishlist::class,
            'product-reviews' => Components\ProductReviews::class,
            'product-search' => Components\ProductSearch::class,
            'advanced-search' => Components\AdvancedSearch::class,
            'checkout' => Components\Checkout::class
        ];
        
        // Enregistrer chaque composant avec Livewire
        foreach ($components as $alias => $class) {
            Livewire::component($alias, $class);
        }
        
        // Vérifier si la classe App\Livewire existe (pour Livewire v3)
        if (class_exists('\App\Livewire')) {
            $this->info('Détection de Livewire v3 - Configuration supplémentaire activée');
            
            // En Livewire v3, nous pouvons avoir besoin d'enregistrer des alias supplémentaires
            // pour la compatibilité avec les vues publiées
        }
    }
}
