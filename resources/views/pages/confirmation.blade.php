@extends('livewire-shop::layouts.app')

@section('title', 'Commande confirmée')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-3">Commande confirmée</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.cart') }}">Panier</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('livewire-shop.checkout') }}">Paiement</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
                </ol>
            </nav>
        </div>
    </div>
    
    {{-- Checkout Steps --}}
    <div class="mb-5">
        <div class="d-flex justify-content-between step-indicator">
            <div class="step">
                <div class="step-icon"><i class="bi bi-cart"></i></div>
                <div class="step-label">Panier</div>
            </div>
            <div class="step">
                <div class="step-icon"><i class="bi bi-credit-card"></i></div>
                <div class="step-label">Paiement</div>
            </div>
            <div class="step active">
                <div class="step-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Success Message --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-md-5 text-center">
                    <div class="mb-4">
                        <div class="confirmation-icon bg-success text-white mb-4">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h2 class="mb-2">Merci pour votre commande !</h2>
                        <p class="text-muted">Votre commande a été confirmée et sera expédiée sous peu.</p>
                    </div>
                    
                    <div class="order-info text-start mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Numéro de commande</h6>
                                <p class="mb-0 fw-bold">{{ $order->reference ?? 'ORD-' . date('Ymd') . '-' . random_int(10000, 99999) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Date</h6>
                                <p class="mb-0">{{ date('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Email</h6>
                                <p class="mb-0">{{ $order->email ?? 'client@exemple.com' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Total</h6>
                                <p class="mb-0 fw-bold text-primary">{{ $order->formatted_total ?? '135,90 €' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="{{ route('livewire-shop.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-shop me-2"></i> Continuer vos achats
                        </a>
                        
                        @if(isset($order))
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="bi bi-file-earmark-text me-2"></i> Voir les détails de la commande
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Order Details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Détails de la commande</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order->items))
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(isset($item->image))
                                                        <img src="{{ asset('storage/' . $item->image) }}" width="40" class="me-3" alt="{{ $item->name }}">
                                                    @endif
                                                    <span>{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ $item->formatted_total }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <span class="ms-3">Chemise Parisienne</span>
                                            </div>
                                        </td>
                                        <td class="text-center">2</td>
                                        <td class="text-end">89,90 €</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <span class="ms-3">Chaussures Élégance</span>
                                            </div>
                                        </td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">46,00 €</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            {{-- Shipping Information --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i> Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->shipping_name ?? 'Jean Dupont' }}</strong><br>
                        {{ $order->shipping_address ?? '123 Rue de la Paix' }}<br>
                        {{ $order->shipping_postal_code ?? '75000' }} {{ $order->shipping_city ?? 'Paris' }}<br>
                        {{ $order->shipping_country ?? 'France' }}<br>
                        <abbr title="Téléphone">Tél:</abbr> {{ $order->shipping_phone ?? '06 12 34 56 78' }}
                    </address>
                </div>
            </div>
            
            {{-- Estimated Delivery --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Livraison estimée</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-box-seam fs-4 text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0">Préparation de la commande</h6>
                            <span class="text-muted small">{{ date('d/m/Y', strtotime('+1 day')) }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-truck fs-4 text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0">Expédition</h6>
                            <span class="text-muted small">{{ date('d/m/Y', strtotime('+2 days')) }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-house fs-4 text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0">Livraison estimée</h6>
                            <span class="text-muted small">{{ date('d/m/Y', strtotime('+3 days')) }} - {{ date('d/m/Y', strtotime('+5 days')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Support Information --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i> Besoin d'aide ?</h5>
                </div>
                <div class="card-body">
                    <p class="small">Si vous avez des questions sur votre commande, n'hésitez pas à nous contacter :</p>
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-primary"></i> support@votreboutique.com</p>
                    <p class="mb-0"><i class="bi bi-telephone me-2 text-primary"></i> 01 23 45 67 89</p>
                </div>
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
    
    .confirmation-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2.5rem;
    }
    
    .order-info {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        padding: 1.5rem;
        border-radius: 8px;
    }
    
    .order-info h6 {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .order-info p {
        font-size: 1rem;
    }
</style>
@endsection
