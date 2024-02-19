<?php

namespace App\Events\Monitor;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class JuryTafelPresenceChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public $toestel, public $count)
    {
    }

    public function broadcastOn()
    {
        return ['monitor.jurytafel.' . $this->toestel];
    }

    public function broadcastAs()
    {
        return 'JuryTafelPresenceChanged';
    }

    public function broadcastWith()
    {
        return [
            'count' => $this->count
        ];
    }
}
