<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components;

use Livewire\Component;
use LaravelLivewireShop\LaravelLivewireShop\Services\OrderService;

class Checkout extends Component
{
    public $step = 1; // 1: Adresses, 2: Révision, 3: Paiement, 4: Confirmation
    public $order = null;

    // Adresse de facturation
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

    // Adresse de livraison
    public $shippingAddress = [];
    public $sameAsbilling = true;

    // Conditions
    public $acceptTerms = false;
    public $acceptPrivacy = false;

    protected $rules = [
        'billingAddress.first_name' => 'required|string|max:255',
        'billingAddress.last_name' => 'required|string|max:255',
        'billingAddress.email' => 'required|email|max:255',
        'billingAddress.phone' => 'required|string|max:20',
        'billingAddress.address' => 'required|string|max:255',
        'billingAddress.city' => 'required|string|max:255',
        'billingAddress.postal_code' => 'required|string|max:10',
        'billingAddress.country' => 'required|string|max:255',
        'acceptTerms' => 'accepted',
        'acceptPrivacy' => 'accepted',
    ];

    public function mount()
    {
        $cart = app('cart')->getCart();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validateStep1();
            $this->step = 2;
        } elseif ($this->step == 2) {
            $this->step = 3;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function validateStep1()
    {
        $rules = [
            'billingAddress.first_name' => 'required|string|max:255',
            'billingAddress.last_name' => 'required|string|max:255',
            'billingAddress.email' => 'required|email|max:255',
            'billingAddress.phone' => 'required|string|max:20',
            'billingAddress.address' => 'required|string|max:255',
            'billingAddress.city' => 'required|string|max:255',
            'billingAddress.postal_code' => 'required|string|max:10',
        ];

        if (!$this->sameAsbilling) {
            $rules = array_merge($rules, [
                'shippingAddress.first_name' => 'required|string|max:255',
                'shippingAddress.last_name' => 'required|string|max:255',
                'shippingAddress.address' => 'required|string|max:255',
                'shippingAddress.city' => 'required|string|max:255',
                'shippingAddress.postal_code' => 'required|string|max:10',
            ]);
        }

        $this->validate($rules);
    }

    public function processOrder()
    {
        $this->validate();

        try {
            $orderService = new OrderService();
            
            $shipping = $this->sameAsbilling ? null : $this->shippingAddress;
            
            $this->order = $orderService->createOrder(
                $this->billingAddress,
                $shipping,
                auth()->id()
            );

            $this->step = 4;
            
            $this->dispatch('show-notification', 'Commande créée avec succès !');
            
        } catch (\Exception $e) {
            $this->addError('order', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire-shop::components.checkout');
    }
}
