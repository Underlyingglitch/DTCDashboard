<?php

namespace App\Events\Scores;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TeamScoresUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $matchday_id;

    public function __construct($matchday_id)
    {
        $this->matchday_id = $matchday_id;
    }

    public function broadcastOn()
    {
        return new Channel('livescores.' . $this->matchday_id);
    }

    public function broadcastAs()
    {
        return 'TeamScoresUpdated';
    }

    public function broadcastWith()
    {
        return [
            'matchday_id' => $this->matchday_id,
        ];
    }
}
