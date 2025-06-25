<?php

namespace VotreNamespace\LaravelLivewireShop\Observers;

use VotreNamespace\LaravelLivewireShop\Models\Product;
use VotreNamespace\LaravelLivewireShop\Services\CacheService;

class ProductObserver
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function created(Product $product)
    {
        $this->clearCache();
    }

    public function updated(Product $product)
    {
        $this->clearCache();
    }

    public function deleted(Product $product)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        $this->cacheService->clearProductCache();
    }
}
