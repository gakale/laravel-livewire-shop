<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;

class CartService
{
    protected $cartKey;
    protected $taxRate;
    protected $shippingCost;
    protected $couponService;

    public function __construct(CouponService $couponService = null)
    {
        $this->cartKey = config('livewire-shop.cart.session_key', 'shop_cart');
        $this->taxRate = config('livewire-shop.tax.rate', 20);
        $this->shippingCost = config('livewire-shop.shipping.cost', 0);
        $this->couponService = $couponService ?: app(CouponService::class);
    }

    protected function getCart(): Collection
    {
        return Session::get($this->cartKey, collect());
    }

    protected function updateCart(Collection $cart)
    {
        Session::put($this->cartKey, $cart);
    }

    public function add(Product $product, int $quantity = 1, array $attributes = [])
    {
        $cart = $this->getCart();

        // Format des attributs : ['color' => 'red', 'size' => 'XL']
        $itemId = $this->generateItemId($product, $attributes);

        $existingItem = $cart->get($itemId);
        $price = $product->current_price; // Use current price (which might be a sale price)

        if ($existingItem) {
            $cart[$itemId]['quantity'] += $quantity;
        } else {
            $cart[$itemId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'original_price' => $product->price, // Keep original price for reference
                'quantity' => $quantity,
                'attributes' => $attributes,
                'image' => $product->image
            ];
        }

        $this->updateCart($cart);

        return $itemId;
    }

    public function update(string $itemId, int $quantity)
    {
        $cart = $this->getCart();

        if ($cart->has($itemId)) {
            if ($quantity <= 0) {
                $this->remove($itemId);
                return;
            }

            $cart[$itemId]['quantity'] = $quantity;
            $this->updateCart($cart);
        }
    }

    public function remove(string $itemId)
    {
        $cart = $this->getCart();
        $cart->forget($itemId);
        $this->updateCart($cart);
    }

    public function clear()
    {
        Session::forget($this->cartKey);
        $this->couponService->removeCoupon();
    }

    public function content()
    {
        return $this->getCart();
    }

    public function count()
    {
        return $this->getCart()->sum('quantity');
    }

    public function isEmpty()
    {
        return $this->getCart()->isEmpty();
    }
    
    public function getSubtotal()
    {
        return $this->getCart()->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
    
    public function getDiscount()
    {
        return $this->couponService->getDiscount();
    }

    public function getTaxAmount()
    {
        // Apply tax after discount
        $afterDiscount = $this->getSubtotal() - $this->getDiscount();
        return max(0, $afterDiscount) * ($this->taxRate / 100);
    }

    public function getShippingCost()
    {
        // Optional: You could implement free shipping for orders above a certain amount
        $minimum = config('livewire-shop.shipping.free_above', 0);
        
        if ($minimum > 0 && $this->getSubtotal() >= $minimum) {
            return 0;
        }
        
        return $this->shippingCost;
    }
    
    public function total()
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscount();
        $tax = $this->getTaxAmount();
        $shipping = $this->getShippingCost();
        
        return max(0, $subtotal - $discount) + $tax + $shipping;
    }

    public function getTotals()
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscount();
        $tax = $this->getTaxAmount();
        $shipping = $this->getShippingCost();
        $total = max(0, $subtotal - $discount) + $tax + $shipping;
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ];
    }

    public function getFormattedTotals()
    {
        $totals = $this->getTotals();
        
        return [  
            'subtotal' => $this->formatPrice($totals['subtotal']),
            'discount' => $this->formatPrice($totals['discount']),
            'tax' => $this->formatPrice($totals['tax']),
            'shipping' => $this->formatPrice($totals['shipping']),
            'total' => $this->formatPrice($totals['total'])
        ];
    }

    public function formatPrice($price)
    {
        $symbol = config('livewire-shop.currency.symbol', '\u20ac');
        $position = config('livewire-shop.currency.position', 'after');

        if ($position === 'before') {
            return $symbol . number_format($price, 2, ',', ' ');
        } else {
            return number_format($price, 2, ',', ' ') . ' ' . $symbol;
        }
    }

    protected function generateItemId(Product $product, array $attributes): string
    {
        if (empty($attributes)) {
            return (string) $product->id;
        }

        ksort($attributes);
        $attributeString = json_encode($attributes);
        
        return $product->id . '_' . md5($attributeString);
    }

    public function getProduct(string $itemId)
    {
        $cart = $this->getCart();
        
        if ($cart->has($itemId)) {
            $item = $cart->get($itemId);
            return Product::find($item['id']);
        }
        
        return null;
    }
    
    public function getAppliedCoupon()
    {
        return $this->couponService->getAppliedCoupon();
    }
    
    protected function attributesMatch($attrs1, $attrs2)
    {
        // Si les deux sont vides, on considère qu'ils correspondent
        if (empty($attrs1) && empty($attrs2)) {
            return true;
        }

        // Si un est vide et l'autre non, ils ne correspondent pas
        if (empty($attrs1) || empty($attrs2)) {
            return false;
        }

        // Comparer chaque attribut
        foreach ($attrs1 as $key => $value) {
            if (!isset($attrs2[$key]) || $attrs2[$key] !== $value) {
                return false;
            }
        }

        foreach ($attrs2 as $key => $value) {
            if (!isset($attrs1[$key])) {
                return false;
            }
        }

        return true;
    }
}
