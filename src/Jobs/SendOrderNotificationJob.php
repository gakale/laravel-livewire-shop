<?php

namespace VotreNamespace\LaravelLivewireShop\Jobs;

use VotreNamespace\LaravelLivewireShop\Models\Order;
use VotreNamespace\LaravelLivewireShop\Mail\OrderStatusUpdateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $status;

    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function handle()
    {
        $email = $this->order->billing_address['email'] ?? null;
        
        if ($email) {
            Mail::to($email)->send(new OrderStatusUpdateMail($this->order, $this->status));
        }
    }
}
