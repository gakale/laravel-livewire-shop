<div>
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="mb-0">Informations de facturation</h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="processOrder">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="first-name" class="form-label">Prénom</label>
                        <input type="text" class="form-control @error('billingAddress.first_name') is-invalid @enderror" id="first-name" wire:model="billingAddress.first_name">
                        @error('billingAddress.first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="last-name" class="form-label">Nom</label>
                        <input type="text" class="form-control @error('billingAddress.last_name') is-invalid @enderror" id="last-name" wire:model="billingAddress.last_name">
                        @error('billingAddress.last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('billingAddress.email') is-invalid @enderror" id="email" wire:model="billingAddress.email">
                        @error('billingAddress.email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control @error('billingAddress.phone') is-invalid @enderror" id="phone" wire:model="billingAddress.phone">
                        @error('billingAddress.phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Adresse</label>
                        <input type="text" class="form-control @error('billingAddress.address') is-invalid @enderror" id="address" wire:model="billingAddress.address">
                        @error('billingAddress.address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" class="form-control @error('billingAddress.city') is-invalid @enderror" id="city" wire:model="billingAddress.city">
                        @error('billingAddress.city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="postal-code" class="form-label">Code postal</label>
                        <input type="text" class="form-control @error('billingAddress.postal_code') is-invalid @enderror" id="postal-code" wire:model="billingAddress.postal_code">
                        @error('billingAddress.postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="country" class="form-label">Pays</label>
                        <select class="form-select @error('billingAddress.country') is-invalid @enderror" id="country" wire:model="billingAddress.country">
                            <option value="France">France</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Canada">Canada</option>
                            <option value="Luxembourg">Luxembourg</option>
                        </select>
                        @error('billingAddress.country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Méthode de paiement</h4>

                <div class="my-3">
                    <div class="form-check">
                        <input id="credit-card" name="payment-method" type="radio" class="form-check-input" checked>
                        <label class="form-check-label" for="credit-card">Carte de crédit</label>
                    </div>
                    <div class="form-check">
                        <input id="paypal" name="payment-method" type="radio" class="form-check-input">
                        <label class="form-check-label" for="paypal">PayPal</label>
                    </div>
                </div>

                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="cc-name" class="form-label">Nom sur la carte</label>
                        <input type="text" class="form-control" id="cc-name">
                        <small class="text-muted">Nom complet tel qu'affiché sur la carte</small>
                    </div>

                    <div class="col-md-6">
                        <label for="cc-number" class="form-label">Numéro de carte</label>
                        <input type="text" class="form-control" id="cc-number">
                    </div>

                    <div class="col-md-3">
                        <label for="cc-expiration" class="form-label">Expiration</label>
                        <input type="text" class="form-control" id="cc-expiration" placeholder="MM/YY">
                    </div>

                    <div class="col-md-3">
                        <label for="cc-cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cc-cvv">
                    </div>
                </div>

                <hr class="my-4">

                <button class="btn btn-primary btn-lg w-100" type="submit">
                    <i class="bi bi-lock"></i> Payer maintenant
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('order-processed', () => {
                alert('Votre commande a été traitée avec succès!');
                // Rediriger vers une page de confirmation
            });
        });
    </script>
</div>
