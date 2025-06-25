<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $table = 'shop_coupons';

    protected $fillable = [
        'code',
        'name', 
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'active' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->active()
            ->where(function($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->where(function($q) {
                $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
            });
    }

    public function canBeUsed($amount = 0)
    {
        if (!$this->active) return false;
        
        $now = Carbon::now();
        
        // Vérifier les dates
        if ($this->starts_at && $this->starts_at > $now) return false;
        if ($this->expires_at && $this->expires_at < $now) return false;
        
        // Vérifier l'usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        // Vérifier le montant minimum
        if ($this->minimum_amount && $amount < $this->minimum_amount) return false;
        
        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->canBeUsed($amount)) return 0;
        
        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }
        
        return min($this->value, $amount); // Ne peut pas dépasser le montant total
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
