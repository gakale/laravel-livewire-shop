@extends('livewire-shop::layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('livewire-shop.index') }}">Boutique</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="card mb-5 product-detail-card">
        <div class="card-body p-0">
            <div class="row g-0">
                {{-- Product Image --}}
                <div class="col-lg-5 position-relative">
                    @if($product->isOnSale())
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger">-{{ $product->discount_percentage }}%</span>
                        </div>
                    @endif
                    
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid h-100 w-100 product-image" 
                            style="object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light h-100" style="min-height: 400px;">
                            <span style="font-size: 5rem;">📦</span>
                        </div>
                    @endif
                </div>
                
                {{-- Product Details --}}
                <div class="col-lg-7">
                    <div class="p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="mb-0">{{ $product->name }}</h1>
                            <livewire:add-to-wishlist :product-id="$product->id" wire:key="wishlist-{{ $product->id }}" />
                        </div>
                        
                        {{-- Ratings --}}
                        <div class="mb-4">
                            @if($product->average_rating > 0)
                                <div class="d-flex align-items-center">
                                    <span class="text-warning me-2">{{ $product->stars_display }}</span>
                                    <span class="text-muted">
                                        {{ number_format($product->average_rating, 1) }}/5 
                                        ({{ $product->reviews_count }} avis)
                                    </span>
                                </div>
                            @else
                                <span class="text-muted small">Aucun avis pour le moment</span>
                            @endif
                        </div>
                        
                        {{-- Price --}}
                        <div class="mb-4">
                            @if($product->isOnSale())
                                <div class="mb-1">
                                    <span class="h3 text-danger me-2">{{ $product->formatted_current_price }}</span>
                                    <span class="text-decoration-line-through text-muted fs-5">{{ $product->formatted_original_price }}</span>
                                </div>
                                <div class="text-success small">Économisez {{ $product->formatted_discount_amount }}</div>
                            @else
                                <span class="h3 text-primary">{{ $product->formatted_current_price }}</span>
                            @endif
                        </div>
                        
                        {{-- Description --}}
                        <div class="mb-4">
                            <p style="white-space: pre-line;">{{ $product->description }}</p>
                        </div>
                        
                        {{-- Stock --}}
                        <div class="mb-4">
                            @if($product->isInStock())
                                <div class="d-flex align-items-center text-success">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <span>
                                        @if($product->stock > 10)
                                            En stock
                                        @else
                                            Seulement {{ $product->stock }} en stock
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="d-flex align-items-center text-danger">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    <span>Rupture de stock</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Add to Cart --}}
                        <div class="mb-4">
                            <livewire:add-to-cart :product="$product" />
                        </div>
                        
                        {{-- Variants --}}
                        @if($product->variants)
                            <div class="card border mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Variantes disponibles</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Option</th>
                                                    <th>Prix</th>
                                                    <th>Stock</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(json_decode($product->variants, true) as $variant)
                                                    <tr>
                                                        <td>{{ $variant['name'] }}</td>
                                                        <td>{{ App\Helpers\PriceFormatter::format($variant['price']) }}</td>
                                                        <td>
                                                            @if($variant['stock'] > 0)
                                                                <span class="text-success">✓</span>
                                                            @else
                                                                <span class="text-danger">✗</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($variant['stock'] > 0)
                                                                <button class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-cart-plus"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Related Products --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mb-5">
        <h3 class="mb-4">Produits similaires</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($relatedProduct->image)
                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text">{{ Str::limit($relatedProduct->description, 50) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">{{ $relatedProduct->formatted_current_price }}</span>
                            <a href="{{ route('livewire-shop.product', $relatedProduct->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    {{-- Reviews Section --}}
    <div class="mb-5">
        <livewire:product-reviews :product="$product" wire:key="reviews-{{ $product->id }}" />
    </div>
</div>
@endsection
