@extends('livewire-shop::layouts.shop')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4">🛍️ Notre Boutique</h1>
        
        {{-- Statistiques de la boutique --}}
        @php
            $stats = app('cache')->getShopStats();
        @endphp
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-primary">{{ $stats['total_products'] }}</h5>
                        <small class="text-muted">Produits disponibles</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-danger">{{ $stats['products_on_sale'] }}</h5>
                        <small class="text-muted">En promotion</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-warning">{{ number_format($stats['average_rating'], 1) }}/5</h5>
                        <small class="text-muted">Note moyenne</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="text-success">{{ $stats['total_reviews'] }}</h5>
                        <small class="text-muted">Avis clients</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Produits en vedette --}}
@php
    $featuredProducts = app('cache')->getFeaturedProducts(4);
@endphp

@if($featuredProducts->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4">⭐ Produits en vedette</h3>
            <div class="row">
                @foreach($featuredProducts as $product)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card product-card h-100 border-warning">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-warning">VEDETTE</span>
                            </div>
                            
                            @if($product->image)
                                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span style="font-size: 3rem;">📦</span>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <div class="text-warning mb-2">{{ $product->stars_display }}</div>
                                <p class="text-primary fw-bold">{{ $product->formatted_current_price }}</p>
                                <div class="mt-auto">
                                    <livewire:add-to-cart :product="$product" :key="'featured-'.$product->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- Recherche et produits --}}
<livewire:advanced-search />
@endsection
