<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use VotreNamespace\LaravelLivewireShop\Models\Product;
use VotreNamespace\LaravelLivewireShop\Models\Review;

class ProductReviews extends Component
{
    public $product;
    public $reviews = [];
    public $formOpen = false;
    
    public $customerName = '';
    public $customerEmail = '';
    public $rating = 5;
    public $comment = '';
    
    protected $rules = [
        'customerName' => 'required|string|min:2|max:100',
        'customerEmail' => 'required|email|max:100',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
    ];
    
    public function mount($productId)
    {
        $this->product = Product::findOrFail($productId);
        $this->loadReviews();
    }
    
    public function loadReviews()
    {
        // Only load approved reviews
        $this->reviews = $this->product->reviews()
            ->approved()
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function toggleForm()
    {
        $this->formOpen = !$this->formOpen;
        
        if ($this->formOpen) {
            // Prefill user data if authenticated
            if (auth()->check()) {
                $this->customerName = auth()->user()->name;
                $this->customerEmail = auth()->user()->email;
            }
        }
    }
    
    public function submitReview()
    {
        $this->validate();
        
        $requiresModeration = config('livewire-shop.reviews.require_moderation', true);
        
        $review = new Review([
            'product_id' => $this->product->id,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'approved' => !$requiresModeration
        ]);
        
        $review->save();
        
        if ($requiresModeration) {
            session()->flash('review-message', 'Merci pour votre avis! Il sera publié après modération.');
        } else {
            session()->flash('review-message', 'Merci pour votre avis!');
            $this->loadReviews();
            $this->product->updateRating();
        }
        
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->reset(['customerName', 'customerEmail', 'rating', 'comment']);
        $this->formOpen = false;
    }
    
    public function render()
    {
        return view('livewire-shop::components.product-reviews');
    }
}
