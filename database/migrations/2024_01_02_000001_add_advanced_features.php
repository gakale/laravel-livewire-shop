<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vérifie si un index existe déjà sur une table
     *
     * @param string $table Le nom de la table
     * @param string $indexName Le nom de l'index
     * @return bool
     */
    private function hasIndex($table, $indexName)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        
        try {
            $doctrineTable = $dbSchemaManager->listTableDetails($table);
            return $doctrineTable->hasIndex($indexName);
        } catch (\Exception $e) {
            return false; // La table n'existe pas encore
        }
    }
    
    public function up()
    {
        // Table des codes promo
        Schema::create('shop_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2);
            $table->decimal('minimum_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Table des avis produits
        Schema::create('shop_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->integer('rating'); // 1-5 stars
            $table->text('comment');
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });

        // Table wishlist
        Schema::create('shop_wishlists', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->timestamps();
            
            // Créer les index de façon sécurisée
            if (!$this->hasIndex('shop_wishlists', 'shop_wishlists_session_id_product_id_index')) {
                $table->index(['session_id', 'product_id']);
            }
            
            if (!$this->hasIndex('shop_wishlists', 'shop_wishlists_user_id_product_id_index')) {
                $table->index(['user_id', 'product_id']);
            }
        });

        // Ajouter des colonnes aux produits
        Schema::table('shop_products', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->datetime('sale_starts_at')->nullable()->after('sale_price');
            $table->datetime('sale_ends_at')->nullable()->after('sale_starts_at');
            $table->json('variants')->nullable()->after('attributes'); // couleurs, tailles, etc.
            $table->decimal('average_rating', 3, 2)->default(0)->after('variants');
            $table->integer('reviews_count')->default(0)->after('average_rating');
        });

        // Créer la table shop_orders si elle n'existe pas déjà
        if (!Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('shipping', 10, 2)->default(0);
                $table->string('currency')->default('USD');
                $table->json('shipping_address')->nullable();
                $table->json('billing_address')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('payment_id')->nullable();
                $table->timestamps();
            });
        }
        
        // Ajouter colonne coupon aux commandes
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->constrained('shop_coupons')->after('currency');
            }
            if (!Schema::hasColumn('shop_orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_id');
            }
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'discount_amount']);
        });
        
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn(['sale_price', 'sale_starts_at', 'sale_ends_at', 'variants', 'average_rating', 'reviews_count']);
        });
        
        Schema::dropIfExists('shop_wishlists');
        Schema::dropIfExists('shop_reviews');
        Schema::dropIfExists('shop_coupons');
    }
};
