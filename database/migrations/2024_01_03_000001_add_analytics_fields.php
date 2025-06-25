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
        $doctrineTable = $dbSchemaManager->listTableDetails($table);
        
        return $doctrineTable->hasIndex($indexName);
    }
    
    public function up()
    {
        // Ajouter user_id aux commandes pour le tracking client
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }
            
            // Vérifier si les index existent avant de les créer
            if (!$this->hasIndex('shop_orders', 'shop_orders_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at']);
            }
            
            if (!$this->hasIndex('shop_orders', 'shop_orders_status_created_at_index')) {
                $table->index(['status', 'created_at']);
            }
        });

        // Table pour tracker les vues de produits
        Schema::create('shop_product_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamp('viewed_at');
            
            $table->index(['product_id', 'viewed_at']);
            $table->index(['ip_address', 'viewed_at']);
        });

        // Table pour les abandons de panier
        Schema::create('shop_cart_abandonments', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->json('cart_data');
            $table->decimal('cart_value', 10, 2);
            $table->string('email')->nullable();
            $table->timestamp('abandoned_at');
            $table->timestamp('recovered_at')->nullable();
            
            $table->index(['abandoned_at', 'recovered_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_cart_abandonments');
        Schema::dropIfExists('shop_product_views');
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
