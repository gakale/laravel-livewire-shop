@extends('livewire-shop::layouts.app')

@section('title', 'Finaliser votre commande')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-3">Finaliser votre commande</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.cart') }}">Panier</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Paiement</li>
                </ol>
            </nav>
        </div>
    </div>
    
    {{-- Checkout Steps --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between step-indicator">
            <div class="step">
                <div class="step-icon"><i class="bi bi-cart"></i></div>
                <div class="step-label">Panier</div>
            </div>
            <div class="step active">
                <div class="step-icon"><i class="bi bi-credit-card-fill"></i></div>
                <div class="step-label">Paiement</div>
            </div>
            <div class="step">
                <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i> Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <livewire:checkout-summary />
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-credit-card-fill me-2"></i> Mode de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="payment-methods">
                        <div class="form-check custom-radio mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                            <label class="form-check-label d-flex align-items-center" for="creditCard">
                                <i class="bi bi-credit-card me-3 fs-4 text-primary"></i>
                                <span>
                                    <strong>Carte de crédit</strong><br>
                                    <span class="text-muted small">Visa, Mastercard, American Express</span>
                                </span>
                            </label>
                        </div>
                        
                        <div class="form-check custom-radio mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                            <label class="form-check-label d-flex align-items-center" for="paypal">
                                <i class="bi bi-paypal me-3 fs-4 text-primary"></i>
                                <span>
                                    <strong>PayPal</strong><br>
                                    <span class="text-muted small">Paiement rapide et sécurisé</span>
                                </span>
                            </label>
                        </div>
                        
                        <div class="form-check custom-radio">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer">
                            <label class="form-check-label d-flex align-items-center" for="bankTransfer">
                                <i class="bi bi-bank me-3 fs-4 text-primary"></i>
                                <span>
                                    <strong>Virement bancaire</strong><br>
                                    <span class="text-muted small">Les instructions vous seront envoyées par email</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            {{-- Order Summary --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Récapitulatif</h5>
                </div>
                <div class="card-body">
                    @php
                        $cart = app('cart')->getCart();
                        $total = app('cart')->getTotal();
                        $couponDiscount = app('cart')->getCouponDiscount();
                        $subtotal = $total + $couponDiscount;
                        $tax = config('livewire-shop.tax.enabled') ? $total * config('livewire-shop.tax.rate') / 100 : 0;
                        $shipping = config('livewire-shop.shipping.enabled') ? ($total >= config('livewire-shop.shipping.free_threshold') ? 0 : config('livewire-shop.shipping.base_cost')) : 0;
                        $grandTotal = $total + $shipping;
                        if (config('livewire-shop.tax.enabled') && !config('livewire-shop.tax.included_in_price')) {
                            $grandTotal += $tax;
                        }
                        
                        // Format functions
                        $formatPrice = function($price) {
                            return number_format($price, 2, ',', ' ') . ' ' . config('livewire-shop.currency.symbol');
                        };
                    @endphp
                    
                    {{-- Cart Items Summary --}}
                    @if($cart && count($cart) > 0)
                        <div class="mb-3">
                            <p class="mb-2 fw-bold">{{ count($cart) }} article(s)</p>
                            @foreach($cart as $item)
                                <div class="d-flex justify-content-between align-items-center small mb-2">
                                    <span>{{ $item['quantity'] }}x {{ Str::limit($item['name'], 20) }}</span>
                                    <span class="text-end">{{ $formatPrice($item['price'] * $item['quantity']) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total:</span>
                        <span>{{ $formatPrice($subtotal) }}</span>
                    </div>
                    
                    @if($couponDiscount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Réduction:</span>
                            <span>-{{ $formatPrice($couponDiscount) }}</span>
                        </div>
                    @endif
                    
                    @if(config('livewire-shop.shipping.enabled'))
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison:</span>
                            <span>{{ $shipping > 0 ? $formatPrice($shipping) : 'Gratuite' }}</span>
                        </div>
                    @endif
                    
                    @if(config('livewire-shop.tax.enabled') && !config('livewire-shop.tax.included_in_price'))
                        <div class="d-flex justify-content-between mb-2">
                            <span>TVA ({{ config('livewire-shop.tax.rate') }}%):</span>
                            <span>{{ $formatPrice($tax) }}</span>
                        </div>
                    @endif
                    
                    <hr>
                    <div class="d-flex justify-content-between mb-3 fw-bold">
                        <span>Total:</span>
                        <span class="h5 mb-0 text-primary">{{ $formatPrice($grandTotal) }}</span>
                    </div>
                    
                    <button class="btn btn-primary w-100 py-2">
                        <i class="bi bi-lock-fill me-2"></i> Payer {{ $formatPrice($grandTotal) }}
                    </button>
                </div>
            </div>
            
            {{-- Security Badges --}}
            <div class="text-center mb-4">
                <div class="d-flex justify-content-center gap-3 mb-2">
                    <div class="security-badge">
                        <i class="bi bi-lock-fill fs-4 text-secondary"></i>
                    </div>
                    <div class="security-badge">
                        <i class="bi bi-credit-card-fill fs-4 text-secondary"></i>
                    </div>
                    <div class="security-badge">
                        <i class="bi bi-shield-lock-fill fs-4 text-secondary"></i>
                    </div>
                </div>
                <p class="small text-muted">Paiement 100% sécurisé</p>
            </div>
            
            <div>
                <a href="{{ route('livewire-shop.cart') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i> Retour au panier
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .step-indicator {
        position: relative;
    }
    
    .step-indicator:before {
        content: '';
        position: absolute;
        top: 14px;
        left: 40px;
        right: 40px;
        height: 2px;
        background: #e0e0e0;
        z-index: 0;
    }
    
    .step {
        position: relative;
        text-align: center;
        z-index: 1;
        padding: 0 10px;
        flex: 1;
    }
    
    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e0e0e0;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
    }
    
    .step.active .step-icon {
        background: var(--bs-primary);
    }
    
    .step-label {
        font-size: 0.85rem;
        color: #666;
    }
    
    .step.active .step-label {
        font-weight: bold;
        color: var(--bs-primary);
    }
    
    .security-badge {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
    }
    
    .custom-radio .form-check-input:checked {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    
    .custom-radio label {
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
    }
    
    .custom-radio input:checked + label {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }
</style>
@endsection
