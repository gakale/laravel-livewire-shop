<div class="wishlist-icon-component position-relative">
    <a href="{{ route('livewire-shop.wishlist') }}" class="btn btn-outline-danger position-relative">
        ❤️ Favoris
        @if($count > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $count }}
            </span>
        @endif
    </a>
</div>
