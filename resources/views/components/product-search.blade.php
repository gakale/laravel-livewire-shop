<div>
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" 
                            placeholder="Rechercher..." wire:model.debounce.300ms="search">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prix</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">Min</span>
                                    <input type="number" class="form-control" min="0" step="10"
                                        wire:model.debounce.500ms="minPrice">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text">Max</span>
                                    <input type="number" class="form-control" min="0" step="10"
                                        wire:model.debounce.500ms="maxPrice">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="onlyInStock" 
                                wire:model="onlyInStock">
                            <label class="form-check-label" for="onlyInStock">
                                Uniquement en stock
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="onlySale" 
                                wire:model="onlySale">
                </div>
                
                {{-- Tri --}}
                <div class="col-md-3 mb-3">
                    <select class="form-select" wire:model="sortBy">
                        <option value="name">Nom</option>
                        <option value="price">Prix</option>
                        <option value="created_at">Date d'ajout</option>
                        <option value="average_rating">Note</option>
                    </select>
                </div>
                
                {{-- Prix min --}}
                <div class="col-md-2 mb-3">
                    <input 
                        type="number" 
                        class="form-control" 
                        placeholder="Prix min"
                        wire:model.debounce.500ms="priceMin"
                        min="0"
                        step="0.01"
                    >
                </div>
                
                {{-- Prix max --}}
                <div class="col-md-2 mb-3">
                    <input 
                        type="number" 
                        class="form-control" 
                        placeholder="Prix max"
                        wire:model.debounce.500ms="priceMax"
                        min="0"
                        step="0.01"
                    >
                </div>
                
                {{-- Direction tri --}}
                <div class="col-md-1 mb-3">
                    <button 
                        class="btn btn-outline-secondary w-100" 
                        wire:click="sortBy('{{ $sortBy }}')"
                        title="Changer l'ordre de tri"
                    >
                        {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                    </button>
                </div>
            </div>
            
            {{-- Filtres checkboxes --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="onSaleOnly" id="onSaleOnly">
                        <label class="form-check-label" for="onSaleOnly">
                            🏷️ Produits en promotion uniquement
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model="inStockOnly" id="inStockOnly">
                        <label class="form-check-label" for="inStockOnly">
                            ✅ En stock uniquement
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Résultats --}}
    <div class="products-grid">
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4" wire:key="product-{{ $product->id }}">
                        <div class="card product-card h-100 position-relative">
                            {{-- Badge promotion --}}
                            @if($product->isOnSale())
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger">-{{ $product->discount_percentage }}%</span>
                                </div>
                            @endif
                            
                            {{-- Bouton wishlist --}}
                            <div class="position-absolute top-0 end-0 m-2">
                                <livewire:add-to-wishlist :product-id="$product->id" :wire:key="'wishlist-'.$product->id" />
                            </div>
                            
                            {{-- Image --}}
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <span style="font-size: 4rem;">📦</span>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                                
                                {{-- Prix --}}
                                <div class="mb-2">
                                    @if($product->isOnSale())
                                        <span class="text-decoration-line-through text-muted me-2">
                                            {{ $product->formatted_original_price }}
                                        </span>
                                        <strong class="text-danger fs-5">{{ $product->formatted_current_price }}</strong>
                                    @else
                                        <strong class="text-primary fs-5">{{ $product->formatted_current_price }}</strong>
                                    @endif
                                </div>
                                
                                {{-- Rating --}}
                                @if($product->reviews_count > 0)
                                    <div class="mb-2">
                                        <span class="text-warning">{{ $product->stars_display }}</span>
                                        <small class="text-muted">({{ $product->reviews_count }})</small>
                                    </div>
                                @endif
                                
                                {{-- Stock --}}
                                @if($product->stock > 0)
                                    <small class="text-success mb-3">✅ En stock</small>
                                @else
                                    <small class="text-danger mb-3">❌ Rupture de stock</small>
                                @endif
                                
                                <div class="mt-auto">
                                    <livewire:add-to-cart :product="$product" :key="'add-to-cart-'.$product->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 4rem;">🔍</div>
                <h5>Aucun produit trouvé</h5>
                <p class="text-muted">Essayez de modifier vos critères de recherche</p>
            </div>
        @endif
    </div>
</div>
