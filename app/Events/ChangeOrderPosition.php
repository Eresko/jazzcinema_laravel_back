<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeOrderPosition implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $message;
    public array $massive = [];

    public function __construct(int $sessionId,$message,$massive)
    {
        $this->sessionId = $sessionId;
        $this->message = $message;
        $this->massive = $massive;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('session.' .$this->sessionId);
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message, 'massive' => $this->massive];
    }
}