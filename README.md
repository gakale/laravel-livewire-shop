# 🛍️ Laravel Livewire Shop Plugin

Un plugin complet et prêt à l'emploi pour créer une boutique en ligne moderne avec Laravel et Livewire.

## ✨ Fonctionnalités Complètes

### 🛒 **Panier & Commandes**
- ✅ Panier dynamique en temps réel
- ✅ Gestion des quantités
- ✅ Persistance du panier
- ✅ Processus de commande complet
- ✅ Gestion des statuts de commande
- ✅ Historique des commandes

### 💰 **Système de Prix & Promotions**
- ✅ Codes promo avec conditions
- ✅ Promotions temporaires
- ✅ Calcul automatique des taxes
- ✅ Frais de port configurables
- ✅ Livraison gratuite conditionnelle

### 👤 **Expérience Client**
- ✅ Liste de souhaits (wishlist)
- ✅ Avis et notes produits
- ✅ Recherche avancée avec filtres
- ✅ Tri par prix, popularité, notes
- ✅ Notifications en temps réel

### 📧 **Notifications & Emails**
- ✅ Confirmation de commande par email
- ✅ Mise à jour du statut de commande
- ✅ Templates d'emails personnalisables
- ✅ Système d'événements complet

### 🎛️ **Administration**
- ✅ Dashboard complet avec analytics
- ✅ Gestion des produits
- ✅ Gestion des commandes
- ✅ Modération des avis
- ✅ Gestion des codes promo
- ✅ Statistiques de vente

### 🚀 **Performance & Sécurité**
- ✅ Cache intelligent
- ✅ Optimisations de base de données
- ✅ Policies de sécurité
- ✅ Validation complète des données
- ✅ Protection CSRF

## 📦 Installation Rapide

### 1. Installation via Composer

```bash
composer require LaravelLivewireShop/laravel-livewire-shop
```

### 2. Installation automatique

```bash
php artisan shop:install
```

Cette commande configure tout automatiquement :
- ✅ Publie les configurations
- ✅ Exécute les migrations
- ✅ Crée des données d'exemple
- ✅ Configure les routes

### 3. Ajout des routes (automatique)

Le fichier `routes/shop.php` est créé automatiquement. Ajoutez dans votre `RouteServiceProvider` :

```php
Route::middleware('web')->group(base_path('routes/shop.php'));
```

## 🎯 Utilisation Immédiate

### Dans vos vues Blade

```blade
{{-- Icône panier avec compteur --}}
<livewire:cart-icon />

{{-- Icône wishlist --}}
<livewire:wishlist-icon />

{{-- Bouton ajouter au panier --}}
<livewire:add-to-cart :product="$product" />

{{-- Bouton wishlist --}}
<livewire:add-to-wishlist :product="$product" />

{{-- Panier complet --}}
<livewire:shop-cart />

{{-- Processus de commande --}}
<livewire:checkout />

{{-- Recherche avancée --}}
<livewire:advanced-search />

{{-- Avis produits --}}
<livewire:product-reviews :product="$product" />

{{-- Code promo --}}
<livewire:coupon-code />
```

### Avec la facade Cart

```php
use LaravelLivewireShop\LaravelLivewireShop\Facades\Cart;

// Ajouter un produit
Cart::add($productId, $quantity, $attributes);

// Obtenir le panier
$items = Cart::getCart();

// Obtenir le total avec détails
$breakdown = Cart::getTotalWithBreakdown();

// Vider le panier
Cart::clear();
```

### Service de commande

```php
use LaravelLivewireShop\LaravelLivewireShop\Services\OrderService;

$orderService = new OrderService();

// Créer une commande
$order = $orderService->createOrder($billingAddress, $shippingAddress, $userId);

// Changer le statut
$orderService->updateOrderStatus($order, 'shipped');
```

## ⚙️ Configuration

### Fichier de configuration `config/livewire-shop.php`

```php
return [
    // Devise
    'currency' => [
        'default' => 'EUR',
        'symbol' => '€',
        'position' => 'after', // 'before' ou 'after'
    ],
    
    // Taxes
    'tax' => [
        'enabled' => true,
        'rate' => 20, // Pourcentage
        'included' => false,
    ],
    
    // Livraison
    'shipping' => [
        'enabled' => true,
        'free_from' => 50, // Livraison gratuite à partir de
        'default_cost' => 5.00,
    ],
    
    // Panier
    'cart' => [
        'session_key' => 'livewire_shop_cart',
        'auto_destroy' => false,
    ],
];
```

## 🗄️ Modèles de Base de Données

### Produits (`shop_products`)
- Informations de base (nom, description, prix)
- Prix de vente et promotions
- Gestion du stock
- Variantes (couleurs, tailles, etc.)
- Notes et avis moyens

### Commandes (`shop_orders`)
- Informations de facturation/livraison
- Totaux détaillés avec taxes
- Statuts de commande
- Codes promo appliqués

### Codes Promo (`shop_coupons`)
- Types : pourcentage ou montant fixe
- Conditions d'utilisation
- Limites d'usage
- Dates de validité

### Avis (`shop_reviews`)
- Notes de 1 à 5 étoiles
- Commentaires clients
- Système de modération

## 🎨 Interface Utilisateur

### Design Moderne
- Interface responsive Bootstrap 5
- Animations CSS fluides
- Icônes émojis pour une touche fun
- Design cards élégant

### Notifications
- Toast notifications en temps réel
- Messages de succès/erreur
- Animations d'état de chargement

### Expérience Mobile
- Interface 100% responsive
- Navigation tactile optimisée
- Performance mobile excellente

## 📊 Analytics & Rapports

### Dashboard Admin
- Statistiques de vente en temps réel
- Top des produits vendus
- Analytics clients
- Graphiques de performance

### Données trackées
- Vues de produits
- Abandons de panier
- Conversion rates
- Valeur panier moyenne

## 🔧 Commandes Artisan

```bash
# Installation complète
php artisan shop:install

# Créer des produits d'exemple
php artisan shop:create-products

# Optimiser la boutique
php artisan shop:optimize

# Nettoyer le cache
php artisan shop:clear-cache
```

## 🎯 Codes Promo d'Exemple

Le plugin installe automatiquement ces codes :

- **BIENVENUE10** - 10% de réduction dès 30€
- **LIVRAISON5** - 5€ de réduction dès 50€
- **FIDELITE20** - 20% de réduction dès 100€

## 🛡️ Sécurité

- Validation complète des données
- Protection contre les injections
- Gestion des sessions sécurisée
- Policies d'autorisation
- Audit trails des commandes

## 🚀 Performance

- Cache intelligent des produits populaires
- Optimisations de base de données
- Lazy loading des images
- Compression des assets
- CDN ready

## 📚 Structure des Fichiers

```
src/
├── Components/         # Composants Livewire
├── Models/            # Modèles Eloquent
├── Services/          # Logique métier
├── Http/Controllers/  # Contrôleurs
├── Mail/              # Templates d'emails
├── Events/            # Événements
├── Listeners/         # Listeners d'événements
├── Console/Commands/  # Commandes Artisan
├── Policies/          # Policies de sécurité
└── Traits/           # Traits réutilisables

resources/views/
├── components/        # Vues des composants
├── pages/            # Pages complètes
├── admin/            # Interface admin
├── emails/           # Templates d'emails
└── layouts/          # Layouts de base
```

## 🤝 Support & Contribution

### Documentation
- [Documentation complète](https://docs.ma-boutique.com)
- [API Reference](https://api.ma-boutique.com)
- [Video Tutorials](https://tutorials.ma-boutique.com)

### Support
- [Discord Community](https://discord.gg/laravel-shop)
- Email: support@ma-boutique.com

### Roadmap
- [ ] Intégration Stripe/PayPal
- [ ] Multi-langues
- [ ] Multi-boutiques
- [ ] App mobile
- [ ] Marketplace features

## 📄 Licence

Ce package est open-source sous licence MIT.

---

**Créé avec ❤️ pour la communauté Laravel**

*Plugin prêt pour la production - Plus de 50 fonctionnalités incluses !*
