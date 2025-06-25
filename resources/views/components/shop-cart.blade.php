<div class="shop-cart-component">
    {{-- Messages de notification --}}
    @if(session()->has('cart-message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('cart-message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    {{-- Panier vide --}}
    @if($cartIsEmpty)
        <div class="text-center py-5">
            <div class="mb-4" style="font-size: 5rem;">🛒</div>
            <h4>Votre panier est vide</h4>
            <p class="text-muted mb-4">C'est le moment de découvrir nos produits!</p>
            <a href="{{ route('livewire-shop.index') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Parcourir la boutique
            </a>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Articles dans votre panier</h5>
                <span class="badge bg-primary">{{ $itemCount }} {{ $itemCount > 1 ? 'articles' : 'article' }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Produit</th>
                                <th scope="col" class="text-center" width="120">Prix</th>
                                <th scope="col" class="text-center" width="150">Quantité</th>
                                <th scope="col" class="text-end" width="130">Total</th>
                                <th scope="col" width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr wire:key="cart-item-{{ $item['id'] }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!empty($item['image']))
                                            <img src="{{ asset('storage/' . $item['image']) }}" width="60" height="60" 
                                                class="me-3" style="object-fit: cover;" alt="{{ $item['name'] }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                style="width: 60px; height: 60px;">
                                                <span style="font-size: 1.5rem;">📦</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item['name'] }}</h6>
                                            @if($item['stock'] <= 5 && $item['stock'] > 0)
                                                <small class="text-danger">Plus que {{ $item['stock'] }} en stock!</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($item['is_on_sale'] ?? false)
                                        <span class="text-danger fw-bold">{{ $item['formatted_current_price'] }}</span>
                                        <br>
                                        <small class="text-decoration-line-through text-muted">
                                            {{ $item['formatted_original_price'] }}
                                        </small>
                                    @else
                                        <span>{{ $item['formatted_current_price'] }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm quantity-selector">
                                        <button 
                                            class="btn btn-outline-secondary" 
                                            type="button"
                                            wire:click="decrementQuantity({{ $item['id'] }})"
                                            wire:loading.attr="disabled"
                                            wire:target="decrementQuantity({{ $item['id'] }})"
                                            @if($item['quantity'] <= 1) disabled @endif
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="text" class="form-control text-center" value="{{ $item['quantity'] }}" readonly>
                                        <button 
                                            class="btn btn-outline-secondary" 
                                            type="button"
                                            wire:click="incrementQuantity({{ $item['id'] }})"
                                            wire:loading.attr="disabled"
                                            wire:target="incrementQuantity({{ $item['id'] }})"
                                            @if($item['quantity'] >= $item['stock']) disabled @endif
                                        >
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-end fw-bold">{{ $item['formatted_total'] }}</td>
                                <td>
                                    <button 
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        wire:click="removeFromCart({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="removeFromCart({{ $item['id'] }})"
                                    >
                                        <span wire:loading.remove wire:target="removeFromCart({{ $item['id'] }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="removeFromCart({{ $item['id'] }})">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <button 
                        type="button" 
                        class="btn btn-outline-danger" 
                        wire:click="clearCart"
                        wire:confirm="Êtes-vous sûr de vouloir vider votre panier ?"
                    >
                        <i class="bi bi-trash"></i> Vider le panier
                    </button>
                    <a href="{{ route('livewire-shop.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Récapitulatif de commande</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Sous-total</td>
                            <td class="text-end">{{ $formattedSubtotal }}</td>
                        </tr>
                        
                        @if($discountAmount > 0)
                        <tr>
                            <td>Remise coupon
                                @if($couponCode)
                                    <span class="badge bg-success">{{ $couponCode }}</span>
                                @endif
                            </td>
                            <td class="text-end text-danger">-{{ $formattedDiscountAmount }}</td>
                        </tr>
                        @endif
                        
                        <tr>
                            <td>TVA ({{ $taxRate }}%)</td>
                            <td class="text-end">{{ $formattedTax }}</td>
                        </tr>
                        <tr>
                            <td>Livraison</td>
                            <td class="text-end">{{ $formattedShipping }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th class="text-end fs-5">{{ $formattedTotal }}</th>
                        </tr>
                    </tbody>
                </table>
                
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('livewire-shop.checkout') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card"></i> Procéder au paiement
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
