@extends('livewire-shop::layouts.app')

@section('title', 'Ma liste de souhaits')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Ma liste de souhaits</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($wishlist->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mb-4">
                @foreach ($wishlist as $item)
                    <div class="col">
                        <div class="card h-100">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}">
                            @else
                                <div class="placeholder-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $item->product->name }}</h5>
                                
                                @if($item->product->average_rating > 0)
                                    <div class="text-warning mb-2">
                                        {{ $item->product->stars_display }}
                                        <span class="text-secondary ms-1">({{ $item->product->reviews_count }})</span>
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    @if($item->product->isOnSale())
                                        <div class="d-flex align-items-center">
                                            <span class="fs-5 fw-bold text-primary me-2">{{ $item->product->formatted_current_price }}</span>
                                            <span class="text-decoration-line-through text-muted">{{ $item->product->formatted_original_price }}</span>
                                            <span class="badge bg-danger ms-auto">-{{ $item->product->discount_percentage }}%</span>
                                        </div>
                                    @else
                                        <span class="fs-5 fw-bold text-primary">{{ $item->product->formatted_current_price }}</span>
                                    @endif
                                    
                                    @if(!$item->product->isInStock())
                                        <span class="badge bg-secondary d-inline-block mt-1">Rupture de stock</span>
                                    @endif
                                </div>
                                
                                <div class="mt-auto d-flex flex-column gap-2">
                                    <a href="{{ route('livewire-shop.product', $item->product->id) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> Voir le produit
                                    </a>
                                    
                                    @if($item->product->isInStock())
                                        <livewire:add-to-cart :product="$item->product" wire:key="add-to-cart-{{ $item->product->id }}" />
                                    @endif
                                    
                                    <livewire:add-to-wishlist :product-id="$item->product->id" wire:key="wishlist-{{ $item->product->id }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('livewire-shop.wishlist.clear') }}" class="btn btn-danger" 
                   onclick="return confirm('Êtes-vous sûr de vouloir vider votre liste de souhaits ?')">
                    <i class="bi bi-trash"></i> Vider ma liste de souhaits
                </a>
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i> Votre liste de souhaits est vide.
            </div>
            
            <div class="mt-4">
                <a href="{{ route('livewire-shop.index') }}" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Continuer mes achats
                </a>
            </div>
        @endif
    </div>
@endsection
