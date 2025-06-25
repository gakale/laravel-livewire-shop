@extends('livewire-shop::layouts.app')

@section('title', 'Votre panier')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-3">Votre panier</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Panier</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row g-4">
        {{-- Main Cart Column --}}
        <div class="col-lg-8">
            {{-- Cart Steps --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between step-indicator">
                    <div class="step active">
                        <div class="step-icon"><i class="bi bi-cart-fill"></i></div>
                        <div class="step-label">Panier</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="step-label">Paiement</div>
                    </div>
                    <div class="step">
                        <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>
            
            {{-- Cart Items --}}
            <livewire:shop-cart />
            
            {{-- Continue Shopping Button --}}
            <div class="mt-4 d-flex">
                <a href="{{ route('livewire-shop.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i> Continuer mes achats
                </a>
            </div>
            
            {{-- Recently Viewed Products --}}
            @if(isset($recentlyViewed) && count($recentlyViewed) > 0)
                <div class="mt-5">
                    <h4 class="mb-3">Récemment consultés</h4>
                    <div class="row row-cols-2 row-cols-md-4 g-3">
                        @foreach($recentlyViewed as $product)
                            <div class="col">
                                <div class="card h-100 product-card">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <div class="card-img-top bg-light d-flex justify-content-center align-items-center" style="height: 150px">
                                            <span class="text-muted">Aucune image</span>
                                        </div>
                                    @endif
                                    <div class="card-body p-3">
                                        <h5 class="card-title fs-6">{{ Str::limit($product->name, 40) }}</h5>
                                        <p class="card-text fw-bold text-primary mb-0">{{ $product->formatted_current_price }}</p>
                                        <div class="mt-2">
                                            <a href="{{ route('livewire-shop.product', $product->id) }}" class="btn btn-sm btn-outline-primary w-100">Voir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Order Summary Column --}}
        <div class="col-lg-4">
            {{-- Coupon Code --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Code promo</h5>
                </div>
                <div class="card-body">
                    <livewire:coupon-code />
                </div>
            </div>
            
            {{-- Customer Assistance --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Besoin d'aide ?</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-truck text-primary fs-5"></i>
                                </div>
                                <div class="ms-3">
                                    <h6>Livraison rapide</h6>
                                    <p class="small text-muted mb-0">Livraison sous 2-5 jours ouvrables</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-arrow-return-left text-primary fs-5"></i>
                                </div>
                                <div class="ms-3">
                                    <h6>Retours gratuits</h6>
                                    <p class="small text-muted mb-0">14 jours pour changer d'avis</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-shield-check text-primary fs-5"></i>
                                </div>
                                <div class="ms-3">
                                    <h6>Paiement sécurisé</h6>
                                    <p class="small text-muted mb-0">Transactions 100% sécurisées</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Contact Information --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Contactez-nous</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-primary"></i> support@votreboutique.com</p>
                    <p class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i> 01 23 45 67 89</p>
                    <p class="mb-0 small text-muted">Du lundi au vendredi, 9h-18h</p>
                </div>
            </div>
            
            {{-- Security Badges --}}
            <div class="mt-4 text-center">
                <div class="d-flex justify-content-center gap-3">
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
                <p class="small text-muted mt-2">Paiements sécurisés via SSL</p>
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
    
    .product-card {
        transition: transform 0.2s ease;
        border: 1px solid rgba(0,0,0,0.08);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection
