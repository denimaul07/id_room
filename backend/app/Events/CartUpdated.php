<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CartUpdated implements ShouldBroadcast
{
    public $cartData;

    public function __construct($cartData)
    {
        $this->cartData = $cartData;
    }

    public function broadcastOn()
    {
        return new Channel('cart');
    }
}
