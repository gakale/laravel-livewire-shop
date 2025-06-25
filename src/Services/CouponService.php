<?php

namespace VotreNamespace\LaravelLivewireShop\Services;

use VotreNamespace\LaravelLivewireShop\Models\Coupon;

class CouponService
{
    public function validateCoupon($code, $amount = 0)
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Code promo invalide'];
        }
        
        if (!$coupon->canBeUsed($amount)) {
            return ['valid' => false, 'message' => 'Ce code promo ne peut pas être utilisé'];
        }
        
        $discount = $coupon->calculateDiscount($amount);
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => "Code promo appliqué ! Réduction de " . app('cart')->formatPrice($discount)
        ];
    }

    public function applyCoupon($code)
    {
        $amount = app('cart')->getSubtotal();
        $result = $this->validateCoupon($code, $amount);
        
        if ($result['valid']) {
            session(['applied_coupon' => $result['coupon']->toArray()]);
        }
        
        return $result;
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
    }

    public function getAppliedCoupon()
    {
        $couponData = session('applied_coupon');
        return $couponData ? new Coupon($couponData) : null;
    }

    public function getDiscount()
    {
        $coupon = $this->getAppliedCoupon();
        if (!$coupon) return 0;
        
        $amount = app('cart')->getSubtotal();
        return $coupon->calculateDiscount($amount);
    }
}
