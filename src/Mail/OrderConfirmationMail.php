<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Mail;

use LaravelLivewireShop\LaravelLivewireShop\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->view('livewire-shop::emails.order-confirmation')
                    ->subject('Confirmation de commande #' . $this->order->order_number);
    }
}
