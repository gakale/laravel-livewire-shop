# Guide d'Installation - Laravel Livewire Shop

Ce guide explique comment installer et configurer tous les fichiers de base nécessaires pour le fonctionnement du package Laravel Livewire Shop.

## Installation Automatique (Recommandée)

```bash
# Installation via Composer
composer require votre-namespace/laravel-livewire-shop

# Installation automatique qui configure tout
php artisan shop:install
```

La commande `shop:install` effectuera automatiquement les actions suivantes :
- Publication des fichiers de configuration
- Publication des vues et templates
- Publication des migrations
- Création du fichier de routes shop.php
- Configuration des middlewares nécessaires
- Création de données d'exemple (optionnel)

## Installation Manuelle (Étape par étape)

Si vous préférez une installation manuelle, suivez ces étapes :

### 1. Publication des Fichiers de Configuration

```bash
php artisan vendor:publish --tag=livewire-shop-config
```

### 2. Publication des Vues

```bash
php artisan vendor:publish --tag=livewire-shop-views
```

### 3. Publication et Exécution des Migrations

```bash
php artisan vendor:publish --tag=livewire-shop-migrations
php artisan migrate
```

### 4. Création du Fichier de Routes

Créez un fichier `routes/shop.php` avec le contenu suivant :

```php
<?php

use Illuminate\Support\Facades\Route;

// Routes publiques
Route::middleware(['web'])->group(function () {
    // Pages principales
    Route::get('/boutique', function () {
        return view('livewire-shop::pages.shop-with-search');
    })->name('shop.index');
    
    Route::get('/produit/{slug}', function ($slug) {
        $product = \VotreNamespace\LaravelLivewireShop\Models\Product::where('slug', $slug)->firstOrFail();
        return view('livewire-shop::pages.product', compact('product'));
    })->name('shop.product.show');
    
    Route::get('/panier', function () {
        return view('livewire-shop::pages.cart');
    })->name('shop.cart');
    
    Route::get('/commander', function () {
        return view('livewire-shop::pages.checkout');
    })->name('shop.checkout');

    // Confirmation de commande
    Route::get('/commande/confirmation/{order}', function ($order) {
        $order = \VotreNamespace\LaravelLivewireShop\Models\Order::findOrFail($order);
        return view('livewire-shop::pages.order-confirmation', compact('order'));
    })->name('shop.order.confirmation');
});

// Routes d'administration
Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('livewire-shop::admin.dashboard');
    })->name('shop.admin.dashboard');
    
    // Autres routes d'administration...
});
```

### 5. Enregistrement des Routes dans RouteServiceProvider

Modifiez votre fichier `app/Providers/RouteServiceProvider.php` :

```php
public function boot()
{
    // ... autres configurations de routes

    // Ajouter ces lignes
    Route::middleware('web')
         ->group(base_path('routes/shop.php'));
}
```

### 6. Création du Middleware Admin

Créez le fichier `app/Http/Middleware/AdminMiddleware.php` :

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
```

### 7. Enregistrement du Middleware

**Pour Laravel 11 et versions ultérieures** :

Enregistrez le middleware dans `bootstrap/app.php` :

```php
// Dans bootstrap/app.php

->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    // ... autres middlewares
    
    // Middleware pour l'accès administrateur à la boutique
    $middleware->alias('admin', \App\Http\Middleware\AdminMiddleware::class);
})
```

**Pour Laravel 10 et versions antérieures** :

Ajoutez le middleware dans `app/Http/Kernel.php` :

```php
protected $routeMiddleware = [
    // ... autres middlewares
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### 8. Création du Champ is_admin

Si votre table d'utilisateurs n'a pas de colonne `is_admin`, créez une migration :

```bash
php artisan make:migration add_is_admin_to_users_table
```

Dans la migration :
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_admin');
    });
}
```

Puis exécutez la migration : `php artisan migrate`

### 9. Données d'Exemple (Optionnel)

```bash
php artisan shop:create-products
```

## Vérification de l'Installation

Pour vérifier que tout est bien installé :

1. Visitez `/boutique` pour voir la page principale de la boutique
2. Visitez `/admin/dashboard` (en étant connecté avec un compte admin) pour voir le tableau de bord d'administration

Si tout s'affiche correctement, votre installation est réussie !

## Structure des Fichiers Publiés

Après installation, la structure suivante sera créée :

```
├── config/
│   └── livewire-shop.php
├── database/migrations/
│   ├── XXXX_XX_XX_create_shop_products_table.php
│   ├── XXXX_XX_XX_create_shop_orders_table.php
│   └── ...autres migrations
├── resources/views/vendor/livewire-shop/
│   ├── components/
│   ├── pages/
│   ├── emails/
│   └── layouts/
└── routes/
    └── shop.php
```

## Prochaines Étapes

1. Configurez les options de votre boutique dans `config/livewire-shop.php`
2. Personnalisez les vues si nécessaire dans `resources/views/vendor/livewire-shop/`
3. Intégrez les composants Livewire dans vos propres vues

Pour plus d'informations sur l'utilisation, consultez le fichier `GUIDE_UTILISATION.md`.
