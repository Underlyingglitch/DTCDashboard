<?php

namespace App\Events;

use App\Models\Group;
use App\Models\ProcessedScore;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ProcessedScoreUpdated implements ShouldBroadcastNow
{
    public $data;
    use InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProcessedScore $ps)
    {
        $this->data = [
            'wedstrijd_id' => $ps->wedstrijd_id,
            'groupnr' => Group::find($ps->group_id)->nr,
            'toestel' => $ps->toestel,
            'completed' => $ps->completed,
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('scorepage.' . $this->data['wedstrijd_id'] . '.' . $this->data['toestel'] . '.' . $this->data['groupnr']);
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ProcessedScoreUpdated';
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
