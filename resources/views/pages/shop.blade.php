@extends('livewire-shop::layouts.app')

@section('title', 'Notre Boutique')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Notre Boutique</h1>
        <div>
            <a href="{{ route('livewire-shop.search') }}" class="btn btn-outline-primary">
                <i class="bi bi-search"></i> Recherche avancée
            </a>
        </div>
    </div>
    
    <div class="row">
        @if(isset($products) && count($products) > 0)
            @foreach($products as $product)
                <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 product-card">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('vendor/livewire-shop/images/' . config('livewire-shop.images.default')) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        
                        @if($product->average_rating > 0)
                            <div class="text-warning mb-2">
                                {{ $product->stars_display }}
                                <span class="text-secondary ms-1">({{ $product->reviews_count }})</span>
                            </div>
                        @endif
                        
                        <div class="mb-2">
                            @if($product->isOnSale())
                                <div class="d-flex align-items-center">
                                    <span class="fs-5 fw-bold text-primary me-2">{{ $product->formatted_current_price }}</span>
                                    <span class="text-decoration-line-through text-muted">{{ $product->formatted_original_price }}</span>
                                    <span class="badge bg-danger ms-auto">-{{ $product->discount_percentage }}%</span>
                                </div>
                            @else
                                <span class="fs-5 fw-bold text-primary">{{ $product->formatted_current_price }}</span>
                            @endif
                        </div>
                        
                        <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('livewire-shop.product', $product) }}" class="btn btn-outline-primary flex-grow-1">
                                <i class="bi bi-eye"></i> Détails
                            </a>
                            <livewire:add-to-wishlist :product-id="$product->id" :wire:key="'wishlist-'.$product->id" />
                        </div>
                        
                        <div class="mt-2">
                            <livewire:add-to-cart :product="$product" :key="'quick-add-'.$product->id" :quickAdd="true" />
                        </div>
                    </div>
                </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <h4>Aucun produit disponible pour le moment</h4>
                    <p>Veuillez revenir plus tard ou contacter l'administrateur du site.</p>
                </div>
                <p class="text-muted small mt-3">Si vous êtes l'administrateur et que vous voyez ce message, veuillez exécuter la commande suivante pour configurer correctement la boutique :</p>
                <pre class="bg-light p-2 d-inline-block">php artisan shop:publish-views</pre>
            </div>
        @endif
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
