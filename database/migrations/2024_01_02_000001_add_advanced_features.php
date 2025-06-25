<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            
            $table->index(['session_id', 'product_id']);
            $table->index(['user_id', 'product_id']);
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

        // Ajouter colonne coupon aux commandes
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->constrained('shop_coupons')->after('currency');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_id');
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
