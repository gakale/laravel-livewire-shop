<div>
    @if (session()->has('wishlist-message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('wishlist-message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="add-to-wishlist-component">
        <button 
            type="button" 
            class="btn {{ $isInWishlist ? 'btn-danger' : 'btn-outline-danger' }} btn-sm"
            wire:click="toggleWishlist"
            wire:loading.attr="disabled"
            title="{{ $isInWishlist ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
        >
            <span wire:loading.remove>
                {{ $isInWishlist ? '❤️' : '🤍' }}
            </span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm" role="status"></span>
            </span>
        </button>
    </div>
</div>
