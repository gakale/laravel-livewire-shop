<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Listeners;

use LaravelLivewireShop\LaravelLivewireShop\Events\OrderCreated;
use LaravelLivewireShop\LaravelLivewireShop\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail
{
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $email = $order->billing_address['email'] ?? null;
        
        if ($email) {
            Mail::to($email)->send(new OrderConfirmationMail($order));
        }
    }
}
