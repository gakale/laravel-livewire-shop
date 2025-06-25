<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'shop_reviews';

    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_email',
        'rating',
        'comment',
        'approved'
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function getStarsAttribute()
    {
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            if ($review->approved) {
                $review->product->updateRating();
            }
        });

        static::deleted(function ($review) {
            $review->product->updateRating();
        });
    }
}
