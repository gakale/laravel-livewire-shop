<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;

class CheckoutSummary extends Component
{
    public $billingAddress = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'postal_code' => '',
        'country' => 'France'
    ];

    public function render()
    {
        return view('livewire-shop::components.checkout-summary');
    }

    public function processOrder()
    {
        $this->validate([
            'billingAddress.first_name' => 'required',
            'billingAddress.last_name' => 'required',
            'billingAddress.email' => 'required|email',
            'billingAddress.address' => 'required',
            'billingAddress.city' => 'required',
            'billingAddress.postal_code' => 'required',
        ]);

        // Traitement de la commande...
        $this->dispatch('order-processed');
    }
}
