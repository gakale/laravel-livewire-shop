<?php

namespace VotreNamespace\LaravelLivewireShop\Policies;

use VotreNamespace\LaravelLivewireShop\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny($user = null)
    {
        return true; // Tous peuvent voir les produits
    }

    public function view($user = null, Product $product)
    {
        return $product->active || ($user && $user->isAdmin());
    }

    public function create($user)
    {
        return $user && $user->isAdmin();
    }

    public function update($user, Product $product)
    {
        return $user && $user->isAdmin();
    }

    public function delete($user, Product $product)
    {
        return $user && $user->isAdmin();
    }
}
