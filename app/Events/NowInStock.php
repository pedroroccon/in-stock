<?php

namespace App\Events;

use App\Stock;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NowInStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Receives the stock 
     * instance
     * 
     * @var \App\Stock
     */
    public $stock;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
