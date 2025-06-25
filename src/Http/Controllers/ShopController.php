<?php

namespace VotreNamespace\LaravelLivewireShop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->latest()
            ->paginate(12);

        return view('livewire-shop::pages.shop', compact('products'));
    }

    public function show(Product $product)
    {
        return view('livewire-shop::pages.product', compact('product'));
    }
    
    public function search()
    {
        // Using Livewire component for searching, filtering, and pagination
        return view('livewire-shop::pages.search');
    }
}
