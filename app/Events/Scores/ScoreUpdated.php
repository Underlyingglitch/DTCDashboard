<?php

namespace App\Events\Scores;

use App\Models\Score;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ScoreUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public $matchday_id, public Score $score)
    {
    }

    public function broadcastOn()
    {
        return ['livescores.' . $this->matchday_id, 'jury'];
    }

    public function broadcastAs()
    {
        return 'ScoreUpdated';
    }

    public function broadcastWith()
    {
        Log::info('ScoreUpdated event fired with score: ' . $this->score->total . ' for matchday: ' . $this->matchday_id);
        return [
            'matchday_id' => $this->matchday_id,
            'startnumber' => $this->score->startnumber,
            'toestel' => $this->score->toestel,
            'score' => $this->score->total,
            'dns' => is_null($this->score->d) ? true : false,
        ];
    }
}
