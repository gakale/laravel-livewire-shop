<?php

namespace VotreNamespace\LaravelLivewireShop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'shop_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'sale_price',
        'sale_starts_at',
        'sale_ends_at',
        'image',
        'attributes',
        'variants',
        'stock',
        'is_active',
        'average_rating',
        'reviews_count'
    ];

    protected $casts = [
        'attributes' => 'array',
        'variants' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'average_rating' => 'decimal:2'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function getCurrentPriceAttribute()
    {
        if ($this->isOnSale()) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function isOnSale()
    {
        if (!$this->sale_price) return false;
        
        $now = now();
        
        if ($this->sale_starts_at && $this->sale_starts_at > $now) return false;
        if ($this->sale_ends_at && $this->sale_ends_at < $now) return false;
        
        return $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        $discount = ($this->price - $this->sale_price) / $this->price * 100;
        return round($discount);
    }
    
    /**
     * Format price according to the configuration
     *
     * @param float $price
     * @return string
     */
    protected function formatPrice($price)
    {
        $currency = config('livewire-shop.currency.symbol', '€');
        $position = config('livewire-shop.currency.position', 'after');
        $decimals = config('livewire-shop.currency.decimals', 2);
        $decimalSeparator = config('livewire-shop.currency.decimal_separator', ',');
        $thousandsSeparator = config('livewire-shop.currency.thousands_separator', ' ');
        
        $formattedPrice = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
        
        return $position === 'before' 
            ? $currency . $formattedPrice 
            : $formattedPrice . ' ' . $currency;
    }

    public function getFormattedCurrentPriceAttribute()
    {
        return $this->formatPrice($this->isOnSale() ? $this->sale_price : $this->price);
    }
    
    /**
     * Get formatted original price
     * 
     * @return string
     */
    public function getFormattedOriginalPriceAttribute()
    {
        return $this->formatPrice($this->price);
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function updateRating()
    {
        $reviews = $this->reviews()->approved();
        $this->update([
            'average_rating' => $reviews->avg('rating') ?: 0,
            'reviews_count' => $reviews->count()
        ]);
    }

    public function getStarsDisplayAttribute()
    {
        $fullStars = floor($this->average_rating);
        $halfStar = ($this->average_rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        return str_repeat('⭐', $fullStars) . 
               ($halfStar ? '⭐' : '') . 
               str_repeat('☆', $emptyStars);
    }

    public function scopeOnSale($query)
    {
        $now = now();
        return $query->whereNotNull('sale_price')
            ->where('sale_price', '<', \DB::raw('price'))
            ->where(function($q) use ($now) {
                $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
            });
    }
}
