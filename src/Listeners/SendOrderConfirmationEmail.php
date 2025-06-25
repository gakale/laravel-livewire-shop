<?php

namespace VotreNamespace\LaravelLivewireShop\Listeners;

use VotreNamespace\LaravelLivewireShop\Events\OrderCreated;
use VotreNamespace\LaravelLivewireShop\Mail\OrderConfirmationMail;
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
