<div class="checkout-component">
    @if($step < 4)
        {{-- Progress Bar --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="progress" style="height: 3px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($step / 3) * 100 }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-{{ $step >= 1 ? 'primary' : 'muted' }}">📍 Adresses</small>
                            <small class="text-{{ $step >= 2 ? 'primary' : 'muted' }}">📋 Révision</small>
                            <small class="text-{{ $step >= 3 ? 'primary' : 'muted' }}">💳 Paiement</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Étape 1: Adresses --}}
    @if($step == 1)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>📍 Informations de livraison</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="nextStep">
                            <h6 class="mb-3">Adresse de facturation</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" wire:model="billingAddress.first_name" required>
                                    @error('billingAddress.first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" wire:model="billingAddress.last_name" required>
                                    @error('billingAddress.last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" wire:model="billingAddress.email" required>
                                    @error('billingAddress.email') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" wire:model="billingAddress.phone" required>
                                    @error('billingAddress.phone') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adresse *</label>
                                <input type="text" class="form-control" wire:model="billingAddress.address" required>
                                @error('billingAddress.address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Ville *</label>
                                    <input type="text" class="form-control" wire:model="billingAddress.city" required>
                                    @error('billingAddress.city') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Code postal *</label>
                                    <input type="text" class="form-control" wire:model="billingAddress.postal_code" required>
                                    @error('billingAddress.postal_code') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Adresse de livraison différente --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" wire:model="sameAsbilling" id="sameAsbilling">
                                <label class="form-check-label" for="sameAsbilling">
                                    Adresse de livraison identique à l'adresse de facturation
                                </label>
                            </div>

                            @if(!$sameAsbilling)
                                <h6 class="mb-3 mt-4">Adresse de livraison</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Prénom *</label>
                                        <input type="text" class="form-control" wire:model="shippingAddress.first_name" required>
                                        @error('shippingAddress.first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom *</label>
                                        <input type="text" class="form-control" wire:model="shippingAddress.last_name" required>
                                        @error('shippingAddress.last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Adresse *</label>
                                    <input type="text" class="form-control" wire:model="shippingAddress.address" required>
                                    @error('shippingAddress.address') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Ville *</label>
                                        <input type="text" class="form-control" wire:model="shippingAddress.city" required>
                                        @error('shippingAddress.city') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Code postal *</label>
                                        <input type="text" class="form-control" wire:model="shippingAddress.postal_code" required>
                                        @error('shippingAddress.postal_code') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="/panier" class="btn btn-outline-secondary">← Retour au panier</a>
                                <button type="submit" class="btn btn-primary">Continuer →</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <livewire:checkout-summary />
            </div>
        </div>
    @endif

    {{-- Étape 2: Révision --}}
    @if($step == 2)
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>📋 Récapitulatif de votre commande</h5>
                    </div>
                    <div class="card-body">
                        <h6>Adresse de facturation</h6>
                        <address>
                            {{ $billingAddress['first_name'] }} {{ $billingAddress['last_name'] }}<br>
                            {{ $billingAddress['address'] }}<br>
                            {{ $billingAddress['postal_code'] }} {{ $billingAddress['city'] }}<br>
                            {{ $billingAddress['email'] }}<br>
                            {{ $billingAddress['phone'] }}
                        </address>

                        @if(!$sameAsbilling && !empty($shippingAddress))
                            <h6 class="mt-4">Adresse de livraison</h6>
                            <address>
                                {{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}<br>
                                {{ $shippingAddress['address'] }}<br>
                                {{ $shippingAddress['postal_code'] }} {{ $shippingAddress['city'] }}
                            </address>
                        @endif

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">← Modifier</button>
                            <button type="button" class="btn btn-primary" wire:click="nextStep">Continuer →</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <livewire:checkout-summary />
            </div>
        </div>
    @endif

    {{-- Étape 3: Paiement --}}
    @if($step == 3)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>💳 Finaliser la commande</h5>
                    </div>
                    <div class="card-body">
                        @error('order') 
                            <div class="alert alert-danger">{{ $message }}</div> 
                        @enderror

                        <div class="alert alert-info">
                            <h6>Mode démo</h6>
                            <p class="mb-0">Cette boutique fonctionne en mode démo. Aucun paiement réel ne sera effectué.</p>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="acceptTerms" id="acceptTerms">
                            <label class="form-check-label" for="acceptTerms">
                                J'accepte les <a href="#" target="_blank">conditions générales de vente</a> *
                            </label>
                            @error('acceptTerms') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" wire:model="acceptPrivacy" id="acceptPrivacy">
                            <label class="form-check-label" for="acceptPrivacy">
                                J'accepte la <a href="#" target="_blank">politique de confidentialité</a> *
                            </label>
                            @error('acceptPrivacy') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">← Retour</button>
                            <button type="button" class="btn btn-success" wire:click="processOrder" wire:loading.attr="disabled">
                                <span wire:loading.remove>🛒 Finaliser la commande</span>
                                <span wire:loading>Traitement en cours...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <livewire:checkout-summary />
            </div>
        </div>
    @endif

    {{-- Étape 4: Confirmation --}}
    @if($step == 4 && $order)
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-4" style="font-size: 4rem;">✅</div>
                        <h2 class="text-success">Commande confirmée !</h2>
                        <p class="lead">Merci pour votre commande {{ $order->order_number }}</p>
                        
                        <div class="alert alert-success">
                            <h6>Que se passe-t-il maintenant ?</h6>
                            <ul class="mb-0 text-start">
                                <li>Vous allez recevoir un email de confirmation</li>
                                <li>Votre commande sera traitée sous 24h</li>
                                <li>Vous recevrez un email avec le suivi d'expédition</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 justify-content-center">
                            <a href="/boutique" class="btn btn-primary">Continuer mes achats</a>
                            <a href="/orders/{{ $order->id }}" class="btn btn-outline-primary">Voir ma commande</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
