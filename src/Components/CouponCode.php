<?php

namespace VotreNamespace\LaravelLivewireShop\Components;

use Livewire\Component;
use VotreNamespace\LaravelLivewireShop\Services\CouponService;

class CouponCode extends Component
{
    public $code = '';
    public $appliedCoupon = null;
    public $message = '';
    public $messageType = '';
    public $applying = false;
    
    public function mount()
    {
        $this->refreshCouponState();
    }
    
    protected function refreshCouponState()
    {
        $this->appliedCoupon = app('coupon')->getAppliedCoupon();
        
        if ($this->appliedCoupon) {
            $this->code = $this->appliedCoupon->code;
        }
    }

    public function applyCoupon()
    {
        $this->applying = true;
        $this->resetMessages();
        
        if (empty($this->code)) {
            $this->setError('Veuillez entrer un code promo');
            $this->applying = false;
            return;
        }
        
        $result = app('coupon')->applyCoupon($this->code);
        
        if ($result['valid']) {
            $this->setSuccess($result['message']);
            $this->refreshCouponState();
            $this->emit('couponApplied');
            $this->emit('cartUpdated');
        } else {
            $this->setError($result['message']);
        }
        
        $this->applying = false;
    }
    
    public function removeCoupon()
    {
        app('coupon')->removeCoupon();
        $this->code = '';
        $this->appliedCoupon = null;
        $this->setSuccess('Code promo retiré avec succès');
        $this->emit('couponRemoved');
        $this->emit('cartUpdated');
    }
    
    protected function setSuccess($message)
    {
        $this->message = $message;
        $this->messageType = 'success';
    }
    
    protected function setError($message)
    {
        $this->message = $message;
        $this->messageType = 'danger';
    }
    
    protected function resetMessages()
    {
        $this->message = '';
        $this->messageType = '';
    }
    
    public function render()
    {
        return view('livewire-shop::components.coupon-code');
    }
}
