<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Services;

use LaravelLivewireShop\LaravelLivewireShop\Models\Order;
use LaravelLivewireShop\LaravelLivewireShop\Models\OrderItem;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;
use LaravelLivewireShop\LaravelLivewireShop\Events\OrderCreated;
use LaravelLivewireShop\LaravelLivewireShop\Events\OrderStatusChanged;
use LaravelLivewireShop\Support\Facades\DB;

class OrderService
{
    public function createOrder($billingAddress, $shippingAddress = null, $userId = null)
    {
        return DB::transaction(function () use ($billingAddress, $shippingAddress, $userId) {
            $cart = app('cart')->getCart();
            
            if (empty($cart)) {
                throw new \Exception('Le panier est vide');
            }

            $breakdown = app('cart')->getTotalWithBreakdown();
            $appliedCoupon = app('coupon')->getAppliedCoupon();

            // Créer la commande
            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $breakdown['subtotal'],
                'discount_amount' => $breakdown['discount'],
                'tax_amount' => $breakdown['tax'],
                'shipping_amount' => $breakdown['shipping'],
                'total' => $breakdown['total'],
                'currency' => config('livewire-shop.currency.default'),
                'coupon_id' => $appliedCoupon?->id,
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress ?: $billingAddress,
                'status' => 'pending'
            ]);

            // Créer les items de commande
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->current_price,
                    'total' => $product->current_price * $item['quantity'],
                    'product_snapshot' => [
                        'name' => $product->name,
                        'description' => $product->description,
                        'image' => $product->image,
                        'attributes' => $item['attributes'] ?? []
                    ]
                ]);

                // Décrémenter le stock
                $product->decrement('stock', $item['quantity']);
            }

            // Incrémenter l'usage du coupon
            if ($appliedCoupon) {
                $appliedCoupon->incrementUsage();
            }

            // Vider le panier
            app('cart')->clear();
            app('coupon')->removeCoupon();

            // Déclencher l'événement
            event(new OrderCreated($order));

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, $status)
    {
        $oldStatus = $order->status;
        $order->update(['status' => $status]);
        
        event(new OrderStatusChanged($order, $oldStatus, $status));
        
        return $order;
    }

    public function cancelOrder(Order $order)
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Cette commande ne peut plus être annulée');
        }

        DB::transaction(function () use ($order) {
            // Restaurer le stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Restaurer l'usage du coupon
            if ($order->coupon) {
                $order->coupon->decrement('used_count');
            }

            $this->updateOrderStatus($order, 'cancelled');
        });

        return $order;
    }

    public function getOrderStatuses()
    {
        return [
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            'refunded' => 'Remboursée'
        ];
    }
}
