<div class="product-reviews mt-5">
    <h3 class="mb-4">Avis clients ({{ count($reviews) }})</h3>

    @if (session()->has('review-message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('review-message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="fs-4 me-2">{{ number_format($product->average_rating, 1) }}</span>
            <span class="text-warning fs-5">{{ $product->stars_display }}</span>
            <span class="ms-2 text-secondary">({{ $product->reviews_count }} avis)</span>
        </div>
        <button class="btn btn-primary" wire:click="toggleForm">
            <i class="bi bi-star"></i> Donner votre avis
        </button>
    </div>

    @if($formOpen)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <div class="product-reviews-component">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    ⭐ Avis clients 
                                    @if($product->reviews_count > 0)
                                        <small class="text-muted">({{ $product->reviews_count }})</small>
                                    @endif
                                </h5>
                                
                                @if($product->reviews_count > 0)
                                    <div class="text-end">
                                        <div class="fs-5">{{ $product->stars_display }}</div>
                                        <small class="text-muted">{{ number_format($product->average_rating, 1) }}/5</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @if($reviews->count() > 0)
                                <div class="reviews-list mb-4">
                                    @foreach($reviews as $review)
                                        <div class="review-item border-bottom py-3" wire:key="review-{{ $review->id }}">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <strong>{{ $review->customer_name }}</strong>
                                                    <div class="text-warning">{{ $review->stars }}</div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3" style="font-size: 3rem;">⭐</div>
                                    <h6>Aucun avis pour le moment</h6>
                                    <p class="text-muted">Soyez le premier à donner votre avis !</p>
                                </div>
                            @endif

                            @if(!$showForm)
                                <button class="btn btn-primary" wire:click="toggleForm">
                                    ✍️ Laisser un avis
                                </button>
                            @else
                                <div class="review-form">
                                    <h6>Votre avis</h6>
                                    <form wire:submit.prevent="submitReview">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nom *</label>
                                                <input type="text" class="form-control" wire:model="customerName" required>
                                                @error('customerName') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Email *</label>
                                                <input type="email" class="form-control" wire:model="customerEmail" required>
                                                @error('customerEmail') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Note *</label>
                                            <div class="rating-input">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-link p-0 {{ $rating >= $i ? 'text-warning' : 'text-muted' }}"
                                                        wire:click="$set('rating', {{ $i }})"
                                                        style="font-size: 1.5rem;"
                                                    >
                                                        {{ $rating >= $i ? '⭐' : '☆' }}
                                                    </button>
                                                @endfor
                                            </div>
                                            @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Commentaire *</label>
                                            <textarea class="form-control" rows="4" wire:model="comment" required 
                                                      placeholder="Partagez votre expérience avec ce produit..."></textarea>
                                            @error('comment') <div class="text-danger small">{{ $message }}</div> @enderror
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                                <span wire:loading.remove>Publier l'avis</span>
                                                <span wire:loading>Publication...</span>
                                            </button>
                                            <button type="button" class="btn btn-secondary" wire:click="toggleForm">
                                                Annuler
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(count($reviews) > 0)
        <div class="reviews-list">
            @foreach($reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h5 class="mb-0">{{ $review->customer_name }}</h5>
                                <div class="text-warning">{{ $review->stars }}</div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Aucun avis pour ce produit pour le moment. Soyez le premier à en laisser un !
        </div>
    @endif
</div>
