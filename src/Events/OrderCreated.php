<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Events;

use LaravelLivewireShop\LaravelLivewireShop\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
