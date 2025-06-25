<div class="wishlist-component">
    @if(session()->has('wishlist-message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('wishlist-message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(count($items) > 0)
        <div class="row">
            @foreach($items as $item)
                <div class="col-lg-4 col-md-6 mb-4" wire:key="wishlist-item-{{ $item['id'] }}">
                    <div class="card h-100">
                        <!-- Product Image -->
                        @if(!empty($item['image']))
                            <img src="{{ asset('storage/' . $item['image']) }}" class="card-img-top" alt="{{ $item['name'] }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span style="font-size: 3rem;">📦</span>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title">{{ $item['name'] }}</h5>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                    wire:click="removeFromWishlist({{ $item['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="removeFromWishlist({{ $item['id'] }})">
                                    <span wire:loading.remove wire:target="removeFromWishlist({{ $item['id'] }})">
                                        <i class="bi bi-x-lg"></i>
                                    </span>
                                    <span wire:loading wire:target="removeFromWishlist({{ $item['id'] }})">
                                        <span class="spinner-border spinner-border-sm"></span>
                                    </span>
                                </button>
                            </div>
                            
                            <!-- Price -->
                            <div class="mb-3">
                                @if($item['is_on_sale'] ?? false)
                                    <span class="text-decoration-line-through text-muted me-2">
                                        {{ $item['formatted_original_price'] }}
                                    </span>
                                    <span class="fw-bold text-danger">{{ $item['formatted_current_price'] }}</span>
                                @else
                                    <span class="fw-bold">{{ $item['formatted_current_price'] }}</span>
                                @endif
                            </div>
                            
                            <!-- In Stock Badge -->
                            <div class="mb-3">
                                @if($item['stock'] > 0)
                                    <span class="badge bg-success">En stock</span>
                                @else
                                    <span class="badge bg-danger">Rupture de stock</span>
                                @endif
                            </div>
                            
                            <!-- Add to Cart Button -->
                            <div class="mt-auto">
                                <button type="button" 
                                    class="btn btn-primary w-100 {{ $item['stock'] <= 0 ? 'disabled' : '' }}"
                                    wire:click="addToCart({{ $item['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $item['id'] }})">
                                    <span wire:loading.remove wire:target="addToCart({{ $item['id'] }})">
                                        <i class="bi bi-cart-plus"></i> Ajouter au panier
                                    </span>
                                    <span wire:loading wire:target="addToCart({{ $item['id'] }})">
                                        <span class="spinner-border spinner-border-sm"></span> Ajout...
                                    </span>
                                </button>
                                
                                <a href="{{ route('livewire-shop.product', $item['id']) }}" class="btn btn-outline-secondary w-100 mt-2">
                                    <i class="bi bi-eye"></i> Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 4rem;">❤️</div>
            <h4>Votre liste de favoris est vide</h4>
            <p class="text-muted mb-4">Explorez notre boutique et ajoutez des produits à vos favoris!</p>
            <a href="{{ route('livewire-shop.index') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Parcourir les produits
            </a>
        </div>
    @endif
</div>
