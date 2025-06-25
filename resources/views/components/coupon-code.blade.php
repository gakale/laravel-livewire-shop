<div class="coupon-code-component">
    @if($message)
        <div class="alert alert-{{ $messageType === 'success' ? 'success' : ($messageType === 'error' ? 'danger' : 'info') }} mb-3" 
             wire:key="coupon-message">
            {{ $message }}
        </div>
    @endif

    @if(!$appliedCoupon)
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">💰 Code promo</h6>
                <div class="input-group">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Entrez votre code promo"
                        wire:model="couponCode"
                        wire:keydown.enter="applyCoupon"
                    >
                    <button 
                        class="btn btn-outline-primary" 
                        type="button"
                        wire:click="applyCoupon"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Appliquer</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                        </span>
                    </button>
                </div>
                @error('couponCode')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @else
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-success mb-1">
                            ✅ Code promo actif
                        </h6>
                        <p class="mb-0">
                            <strong>{{ $appliedCoupon['code'] }}</strong> - {{ $appliedCoupon['name'] }}
                        </p>
                        <small class="text-muted">
                            Réduction : 
                            @if($appliedCoupon['type'] === 'percentage')
                                {{ $appliedCoupon['value'] }}%
                            @else
                                {{ app('cart')->formatPrice($appliedCoupon['value']) }}
                            @endif
                        </small>
                    </div>
                    <button 
                        class="btn btn-outline-danger btn-sm"
                        wire:click="removeCoupon"
                        title="Supprimer le code promo"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('hide-message', () => {
            setTimeout(() => {
                @this.set('message', '');
            }, 5000);
        });
    });
</script>
