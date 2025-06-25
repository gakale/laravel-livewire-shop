<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Observers;

use LaravelLivewireShop\LaravelLivewireShop\Models\Product;
use LaravelLivewireShop\LaravelLivewireShop\Services\CacheService;

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
