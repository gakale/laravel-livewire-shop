# Guide d'Utilisation - Laravel Livewire Shop

## 1. Installation et Configuration

### Installation rapide
```bash
# Installation via Composer
composer require votre-namespace/laravel-livewire-shop

# Installation automatique
php artisan shop:install
```

### Configuration de base
```php
// Dans config/livewire-shop.php
return [
    'currency' => [
        'default' => 'EUR',
        'symbol' => '€',
        'position' => 'after',
    ],
    'tax' => [
        'enabled' => true,
        'rate' => 20,
    ],
    'shipping' => [
        'enabled' => true,
        'free_from' => 50,
    ],
];
```

## 2. Afficher des Produits

### Page boutique
```php
// Dans routes/web.php
Route::get('/boutique', function() {
    return view('livewire-shop::pages.shop-with-search');
});
```

### Afficher un produit spécifique
```php
// Dans un contrôleur
$product = \VotreNamespace\LaravelLivewireShop\Models\Product::findOrFail($id);
return view('livewire-shop::pages.product', compact('product'));
```

## 3. Gestion du Panier

### Ajouter un bouton "Ajouter au panier"
```blade
<livewire:add-to-cart :product="$product" />
```

### Afficher le contenu du panier
```blade
<livewire:shop-cart />
```

### Manipulation du panier via PHP
```php
use VotreNamespace\LaravelLivewireShop\Facades\Cart;

// Ajouter un produit
Cart::add($productId, $quantity);

// Mettre à jour la quantité
Cart::update($cartItemId, $newQuantity);

// Supprimer un article
Cart::remove($cartItemId);

// Vider le panier
Cart::clear();

// Obtenir le total
$total = Cart::getTotal();
```

## 4. Processus de Commande

### Ajouter le composant Checkout
```blade
<livewire:checkout />
```

### Créer une commande via le service
```php
use VotreNamespace\LaravelLivewireShop\Services\OrderService;

$orderService = new OrderService();

$billingAddress = [
    'first_name' => 'Jean',
    'last_name' => 'Dupont',
    'email' => 'jean@exemple.fr',
    'address' => '123 Rue de Paris',
    'city' => 'Paris',
    'postal_code' => '75001',
    'country' => 'France',
    'phone' => '0123456789'
];

$order = $orderService->createOrder($billingAddress, null, auth()->id());
```

### Mettre à jour le statut d'une commande
```php
$orderService->updateOrderStatus($order, 'shipped');
```

## 5. Recherche Avancée

### Ajouter le composant de recherche
```blade
<livewire:advanced-search />
```

### Paramètres de recherche disponibles
- Mot-clé
- Catégorie
- Prix min/max
- Stock minimum
- En promotion
- Note minimum
- Tri (prix, popularité, date)

## 6. Codes Promo

### Ajouter un champ code promo
```blade
<livewire:coupon-code />
```

### Créer un code promo
```php
use VotreNamespace\LaravelLivewireShop\Models\Coupon;

Coupon::create([
    'code' => 'BIENVENUE10',
    'type' => 'percentage',
    'value' => 10,
    'min_order_amount' => 30,
    'max_uses' => 100,
    'starts_at' => now(),
    'expires_at' => now()->addMonth(),
]);
```

## 7. Emails et Notifications

### Email de confirmation automatique
Les emails de confirmation sont envoyés automatiquement lors de la création d'une commande via l'événement `OrderCreated`.

### Configuration de l'email de confirmation
```php
// Dans AppServiceProvider ou un fournisseur dédié
$this->app->singleton('mail.order_confirmation_template', function() {
    return 'livewire-shop::emails.order-confirmation';
});
```

## 8. Cache et Performance

### Utiliser le CacheService
```php
use VotreNamespace\LaravelLivewireShop\Services\CacheService;

$cacheService = new CacheService();

// Obtenir les produits populaires
$popularProducts = $cacheService->getPopularProducts();

// Obtenir les produits en promotion
$saleProducts = $cacheService->getSaleProducts();

// Obtenir les statistiques de la boutique
$stats = $cacheService->getShopStats();
```

### Rafraîchir le cache
```bash
php artisan shop:optimize
```

## 9. Analytics

### Utiliser le trait HasAnalytics
```php
use VotreNamespace\LaravelLivewireShop\Traits\HasAnalytics;

class ShopController extends Controller
{
    use HasAnalytics;
    
    public function dashboard()
    {
        return view('admin.dashboard', [
            'salesByDay' => $this->getSalesByDay(),
            'topProducts' => $this->getTopSellingProducts(),
            'averageOrderValue' => $this->getAverageOrderValue(),
            'customerRetention' => $this->getCustomerRetentionRate(),
        ]);
    }
}
```

## 10. Exemples d'Intégration

### Layout de base
```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Boutique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">Ma Boutique</a>
            <div class="d-flex">
                <livewire:cart-icon />
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <footer class="bg-light py-4">
        <div class="container text-center">
            <p>&copy; 2025 Ma Boutique</p>
        </div>
    </footer>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Page d'accueil
```blade
@extends('layouts.shop')

@section('content')
    <h1>Bienvenue dans notre boutique</h1>
    
    <!-- Produits en vedette -->
    <h2>Produits en vedette</h2>
    <div class="row">
        @foreach(app('cache')->getFeaturedProducts(4) as $product)
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ $product->image }}" class="card-img-top">
                    <div class="card-body">
                        <h5>{{ $product->name }}</h5>
                        <p>{{ $product->formatted_price }}</p>
                        <livewire:add-to-cart :product="$product" :key="'product-'.$product->id" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Lien vers la boutique -->
    <div class="text-center">
        <a href="/boutique" class="btn btn-primary">Voir tous nos produits</a>
    </div>
@endsection
```

## 11. Personnalisation

### Étendre un modèle
```php
use VotreNamespace\LaravelLivewireShop\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    // Ajoutez vos méthodes personnalisées ici
    
    public function getDiscountPercentageAttribute()
    {
        if (!$this->sale_price || $this->price == 0) return 0;
        return round(100 - ($this->sale_price * 100 / $this->price));
    }
}
```

### Étendre un composant Livewire
```php
use VotreNamespace\LaravelLivewireShop\Components\ShopCart as BaseShopCart;

class CustomShopCart extends BaseShopCart
{
    // Personnalisez le comportement du panier
    
    public function addGift()
    {
        // Ajouter un cadeau si le montant dépasse un certain seuil
    }
}
```

Pour plus de détails et d'exemples, consultez la documentation complète.
