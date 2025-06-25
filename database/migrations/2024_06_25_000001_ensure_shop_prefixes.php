<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureShopPrefixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Vérifier si l'ancienne table 'products' existe (sans préfixe)
        if (Schema::hasTable('products') && !Schema::hasTable('shop_products')) {
            // Renommer la table products en shop_products si nécessaire
            Schema::rename('products', 'shop_products');
        }
        
        // Vérifier si l'ancienne table 'orders' existe (sans préfixe)
        if (Schema::hasTable('orders') && !Schema::hasTable('shop_orders')) {
            // Renommer la table orders en shop_orders si nécessaire
            Schema::rename('orders', 'shop_orders');
        }
        
        // Vérifier si l'ancienne table 'coupons' existe (sans préfixe)
        if (Schema::hasTable('coupons') && !Schema::hasTable('shop_coupons')) {
            // Renommer la table coupons en shop_coupons si nécessaire
            Schema::rename('coupons', 'shop_coupons');
        }
        
        // Vérifier si l'ancienne table 'wishlists' existe (sans préfixe)
        if (Schema::hasTable('wishlists') && !Schema::hasTable('shop_wishlists')) {
            // Renommer la table wishlists en shop_wishlists si nécessaire
            Schema::rename('wishlists', 'shop_wishlists');
        }
        
        // Vérifier si l'ancienne table 'reviews' existe (sans préfixe)
        if (Schema::hasTable('reviews') && !Schema::hasTable('shop_reviews')) {
            // Renommer la table reviews en shop_reviews si nécessaire
            Schema::rename('reviews', 'shop_reviews');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cette migration ne devrait pas être annulée car elle corrige un problème de cohérence
    }
}
