<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration monétaire pour la boutique
    |
    */
    'currency' => [
        'default' => 'EUR',
        'symbol' => '€',
        'position' => 'after', // 'before' ou 'after'
        'decimals' => 2,
        'decimal_separator' => ',',
        'thousands_separator' => ' ',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des taxes
    |
    */
    'tax' => [
        'enabled' => true,
        'rate' => 20, // Pourcentage de TVA
        'included_in_price' => true, // Si les prix incluent déjà la TVA
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des frais de livraison
    |
    */
    'shipping' => [
        'enabled' => true,
        'free_threshold' => 50, // Livraison gratuite à partir de ce montant
        'base_cost' => 5.99, // Coût de base de la livraison
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration du panier
    |
    */
    'cart' => [
        'session_key' => 'livewire_shop_cart',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Wishlist Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration de la liste de souhaits
    |
    */
    'wishlist' => [
        'session_key' => 'livewire_shop_wishlist',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Reviews Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des avis clients
    |
    */
    'reviews' => [
        'require_approval' => true,
        'allow_guest' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Coupons Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des codes promo
    |
    */
    'coupons' => [
        'session_key' => 'livewire_shop_coupon',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des images
    |
    */
    'images' => [
        'storage_path' => 'public/shop/products',
        'default' => 'default-product.jpg',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des préfixes de routes
    |
    */
    'routes' => [
        'shop_prefix' => 'boutique',
        'cart_prefix' => 'panier',
        'wishlist_prefix' => 'favoris',
    ],
];
