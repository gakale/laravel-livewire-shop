<?php

namespace VotreNamespace\LaravelLivewireShop\Services;

use Illuminate\Support\Facades\Cache;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class CacheService
{
    public function getPopularProducts($limit = 10)
    {
        return Cache::remember('shop.popular_products', 3600, function () use ($limit) {
            return Product::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getFeaturedProducts($limit = 8)
    {
        return Cache::remember('shop.featured_products', 3600, function () use ($limit) {
            return Product::active()
                ->where('average_rating', '>=', 4)
                ->orderBy('average_rating', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getSaleProducts($limit = 12)
    {
        return Cache::remember('shop.sale_products', 1800, function () use ($limit) {
            return Product::onSale()
                ->orderBy('sale_price', 'asc')
                ->take($limit)
                ->get();
        });
    }

    public function getShopStats()
    {
        return Cache::remember('shop.stats', 3600, function () {
            return [
                'total_products' => Product::active()->count(),
                'products_on_sale' => Product::onSale()->count(),
                'average_rating' => Product::where('reviews_count', '>', 0)->avg('average_rating'),
                'total_reviews' => \VotreNamespace\LaravelLivewireShop\Models\Review::approved()->count(),
            ];
        });
    }

    public function clearProductCache()
    {
        Cache::forget('shop.popular_products');
        Cache::forget('shop.featured_products');
        Cache::forget('shop.sale_products');
        Cache::forget('shop.stats');
    }
}
