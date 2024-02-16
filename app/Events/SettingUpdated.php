<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $key, public mixed $value)
    {
    }

    public function broadcastOn()
    {
        return ['settings.' . $this->key, 'settings.all'];
    }

    public function broadcastAs()
    {
        return 'SettingUpdated';
    }

    public function broadcastWith()
    {
        return [
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
