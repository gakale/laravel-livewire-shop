<div>
    @if(session()->has('cart-message'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div class="toast show align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('cart-message') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    @endif
    
    @if($product->isInStock())
        @if(isset($quickAdd) && $quickAdd)
            <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" 
                    class="btn {{ isset($buttonSize) ? 'btn-'.$buttonSize : '' }} {{ isset($buttonOutline) && $buttonOutline ? 'btn-outline-primary' : 'btn-primary' }}" 
                    title="Ajouter au panier"
                    {{ isset($ariaLabel) ? 'aria-label='.$ariaLabel : '' }}>
                <div wire:loading.remove wire:target="addToCart">
                    <i class="bi bi-cart-plus"></i>
                    @if(!isset($iconOnly) || !$iconOnly)
                        <span class="ms-1">Ajouter</span>
                    @endif
                </div>
                <div wire:loading wire:target="addToCart">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
            </button>
        @else
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    @if(!empty($product->attributes))
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold text-muted mb-3 small">Options du produit</h6>
                            @foreach($product->attributes as $key => $values)
                                <div class="mb-3">
                                    <label for="attribute-{{ $key }}" class="form-label">{{ ucfirst($key) }}</label>
                                    <select id="attribute-{{ $key }}" wire:model="selectedAttributes.{{ $key }}" class="form-select">
                                        <option value="">Choisir {{ strtolower($key) }}</option>
                                        @foreach(explode(', ', $values) as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedAttributes.'.$key) 
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold text-muted mb-3 small">Quantité</h6>
                        <div class="d-flex align-items-center">
                            <div class="input-group" style="width: 130px;">
                                <button type="button" class="btn btn-outline-secondary" 
                                        wire:click="decrement" 
                                        wire:loading.attr="disabled"
                                        wire:target="decrement, increment, quantity"
                                        {{ $quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" id="quantity" 
                                       class="form-control text-center" 
                                       wire:model.lazy="quantity" 
                                       min="1" 
                                       max="{{ min($product->stock, 99) }}">
                                <button type="button" class="btn btn-outline-secondary" 
                                        wire:click="increment" 
                                        wire:loading.attr="disabled"
                                        wire:target="decrement, increment, quantity"
                                        {{ $quantity >= min($product->stock, 99) ? 'disabled' : '' }}>
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            @if($product->stock < 10)
                                <span class="text-muted ms-3 small">{{ $product->stock }} disponibles</span>
                            @endif
                        </div>
                        @error('quantity') 
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button" 
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center" 
                            style="height: 46px;"
                            wire:click="addToCart"
                            wire:loading.attr="disabled"
                            wire:target="addToCart">
                        <div wire:loading.remove wire:target="addToCart">
                            <i class="bi bi-cart-plus me-2"></i> Ajouter au panier
                        </div>
                        <div wire:loading wire:target="addToCart">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Ajout en cours...
                        </div>
                    </button>
                    
                    @if($product->stock < 5)
                        <div class="text-danger small mt-2 text-center">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Dépêchez-vous ! Il n'en reste que {{ $product->stock }} en stock !
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>
                <strong>Rupture de stock</strong><br>
                <small>Ce produit n'est malheureusement plus disponible</small>
            </div>
        </div>
        
        <button class="btn btn-outline-secondary w-100 mt-2" disabled>
            <i class="bi bi-bell me-2"></i> M'alerter quand disponible
        </button>
    @endif
</div>
