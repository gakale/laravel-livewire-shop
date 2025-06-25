<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use Livewire\WithPagination;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class AdvancedSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $inStock = true;
    public $onSale = false;
    public $minRating = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'inStock' => ['except' => true],
        'onSale' => ['except' => false],
        'minRating' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'category', 'minPrice', 'maxPrice', 
            'inStock', 'onSale', 'minRating'
        ]);
    }

    public function getProductsProperty()
    {
        $query = Product::query()->active();

        // Recherche textuelle
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filtres de prix
        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        // Filtre stock
        if ($this->inStock) {
            $query->where('stock', '>', 0);
        }

        // Filtre promotions
        if ($this->onSale) {
            $query->onSale();
        }

        // Filtre note minimale
        if ($this->minRating) {
            $query->where('average_rating', '>=', $this->minRating);
        }

        // Tri
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire-shop::components.advanced-search', [
            'products' => $this->products
        ]);
    }
}
