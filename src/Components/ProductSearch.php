<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use Livewire\WithPagination;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSearch extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 12;
    public $minPrice = 0;
    public $maxPrice = 1000;
    public $onlyInStock = false;
    public $onlySale = false;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 12],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 1000],
        'onlyInStock' => ['except' => false],
        'onlySale' => ['except' => false],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingMinPrice()
    {
        $this->resetPage();
    }
    
    public function updatingMaxPrice()
    {
        $this->resetPage();
    }
    
    public function updatingOnlyInStock()
    {
        $this->resetPage();
    }
    
    public function updatingOnlySale()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortBy = $field;
    }
    
    public function render()
    {
        $query = Product::query()
            ->active()
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->minPrice > 0, function ($query) {
                return $query->where(function ($q) {
                    $q->where('price', '>=', $this->minPrice)
                      ->orWhere(function ($q2) {
                          $q2->whereNotNull('sale_price')
                             ->where('sale_price', '>=', $this->minPrice);
                      });
                });
            })
            ->when($this->maxPrice < config('livewire-shop.filter.max_price', 1000), function ($query) {
                return $query->where(function ($q) {
                    $q->where('price', '<=', $this->maxPrice)
                      ->orWhere(function ($q2) {
                          $q2->whereNotNull('sale_price')
                             ->where('sale_price', '<=', $this->maxPrice);
                      });
                });
            })
            ->when($this->onlyInStock, function ($query) {
                return $query->inStock();
            })
            ->when($this->onlySale, function ($query) {
                return $query->onSale();
            });
            
        // Handle price sorting considering sale prices
        if ($this->sortBy === 'price') {
            $query->orderByRaw(
                'CASE WHEN sale_price IS NOT NULL AND sale_starts_at <= ? AND (sale_ends_at IS NULL OR sale_ends_at >= ?) ' .
                'THEN sale_price ELSE price END ' . 
                $this->sortDirection,
                [now(), now()]
            );
        } 
        // Handle other sorting types
        else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }
        
        $products = $query->paginate($this->perPage);
        
        return view('livewire-shop::components.product-search', [
            'products' => $products
        ]);
    }
}
