<?php

namespace VotreNamespace\LaravelLivewireShop\Console\Commands;

use Illuminate\Console\Command;
use VotreNamespace\LaravelLivewireShop\Models\Product;
use VotreNamespace\LaravelLivewireShop\Models\Review;

class OptimizeShopCommand extends Command
{
    protected $signature = 'shop:optimize';
    protected $description = 'Optimiser les données de la boutique';

    public function handle()
    {
        $this->info('🚀 Optimisation de la boutique...');

        $this->updateProductRatings();
        $this->cleanupExpiredCoupons();
        $this->updateProductPopularity();

        $this->info('✅ Optimisation terminée !');
    }

    private function updateProductRatings()
    {
        $this->line('📊 Mise à jour des notes produits...');
        
        Product::chunk(100, function ($products) {
            foreach ($products as $product) {
                $product->updateRating();
            }
        });
    }

    private function cleanupExpiredCoupons()
    {
        $this->line('🧹 Nettoyage des codes promo expirés...');
        
        $expired = \VotreNamespace\LaravelLivewireShop\Models\Coupon::where('expires_at', '<', now())
            ->where('active', true)
            ->update(['active' => false]);
            
        $this->line("   • {$expired} codes promo expirés désactivés");
    }

    private function updateProductPopularity()
    {
        $this->line('📈 Mise à jour de la popularité des produits...');
        
        // Mettre à jour le cache des produits populaires
        app('cache')->clearProductCache();
        app('cache')->getPopularProducts();
        app('cache')->getFeaturedProducts();
        app('cache')->getSaleProducts();
    }
}
