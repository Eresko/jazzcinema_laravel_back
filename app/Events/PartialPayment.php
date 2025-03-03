<?php

namespace App\Events;

use App\Models\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartialPayment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $sessionId)
    {
        $this->sessionId = $sessionId;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('session.' .$this->sessionId);
    }

    public function broadcastWith()
    {
        return ['message' => 'partial payment'];
    }
}