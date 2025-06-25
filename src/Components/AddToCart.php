<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use VotreNamespace\LaravelLivewireShop\Facades\Cart;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class AddToCart extends Component
{
    public $product;
    public $quantity = 1;
    public $selectedAttributes = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        
        // Initialiser les attributs par défaut si disponibles
        if (!empty($product->attributes)) {
            foreach ($product->attributes as $key => $values) {
                $options = explode(', ', $values);
                if (!empty($options)) {
                    $this->selectedAttributes[$key] = $options[0];
                }
            }
        }
    }

    public function render()
    {
        return view('livewire-shop::components.add-to-cart');
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }

        Cart::add($this->product->id, $this->quantity, $this->selectedAttributes);
        
        $this->dispatch('cartUpdated');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Produit ajouté au panier!'
        ]);
    }
}
