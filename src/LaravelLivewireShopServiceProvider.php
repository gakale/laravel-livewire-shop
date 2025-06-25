<?php

namespace VotreNamespace\LaravelLivewireShop;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use VotreNamespace\LaravelLivewireShop\Console\Commands\InstallShopCommand;
use VotreNamespace\LaravelLivewireShop\Console\Commands\OptimizeShopCommand;
use VotreNamespace\LaravelLivewireShop\Console\Commands\CreateProductsCommand;
use VotreNamespace\LaravelLivewireShop\View\Components\ProductCard;
use VotreNamespace\LaravelLivewireShop\Services\CacheService;
use VotreNamespace\LaravelLivewireShop\Services\OrderService;

class LaravelLivewireShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Enregistrer les composants Livewire
        $this->registerLivewireComponents();
        
        // Enregistrer les composants Blade
        $this->registerBladeComponents();
        
        // Publier les configurations
        $this->publishConfigurations();
        
        // Publier les migrations
        $this->publishMigrations();
        
        // Publier les vues
        $this->publishViews();
        
        // Publier les stubs
        $this->publishStubs();
        
        // Enregistrer les commandes
        $this->registerCommands();
        
        // Charger les routes
        $this->loadRoutes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('cache', function ($app) {
            return new CacheService();
        });
        
        $this->app->singleton('order', function ($app) {
            return new OrderService();
        });
    }

    /**
     * Enregistrer les composants Livewire.
     *
     * @return void
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('add-to-cart', \VotreNamespace\LaravelLivewireShop\Components\AddToCart::class);
        Livewire::component('cart-icon', \VotreNamespace\LaravelLivewireShop\Components\CartIcon::class);
        Livewire::component('shop-cart', \VotreNamespace\LaravelLivewireShop\Components\ShopCart::class);
        Livewire::component('checkout', \VotreNamespace\LaravelLivewireShop\Components\Checkout::class);
        Livewire::component('advanced-search', \VotreNamespace\LaravelLivewireShop\Components\AdvancedSearch::class);
        Livewire::component('coupon-code', \VotreNamespace\LaravelLivewireShop\Components\CouponCode::class);
    }

    /**
     * Enregistrer les composants Blade.
     *
     * @return void
     */
    protected function registerBladeComponents(): void
    {
        Blade::component('product-card', ProductCard::class);
    }

    /**
     * Publier les configurations.
     *
     * @return void
     */
    protected function publishConfigurations(): void
    {
        $this->publishes([
            __DIR__ . '/../config/livewire-shop.php' => config_path('livewire-shop.php'),
        ], 'livewire-shop-config');
    }

    /**
     * Publier les migrations.
     *
     * @return void
     */
    protected function publishMigrations(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'livewire-shop-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Publier les vues.
     *
     * @return void
     */
    protected function publishViews(): void
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-shop'),
        ], 'livewire-shop-views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'livewire-shop');
    }
    
    /**
     * Publier les stubs pour l'installation.
     *
     * @return void
     */
    protected function publishStubs(): void
    {
        $this->publishes([
            __DIR__ . '/../stubs' => base_path('stubs/livewire-shop'),
        ], 'livewire-shop-stubs');
    }

    /**
     * Enregistrer les commandes.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallShopCommand::class,
                OptimizeShopCommand::class,
                CreateProductsCommand::class,
            ]);
        }
    }
    
    /**
     * Charger les routes.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        // Auto-load routes if they exist
        if (file_exists(base_path('routes/shop.php'))) {
            $this->loadRoutesFrom(base_path('routes/shop.php'));
        }
    }
}
