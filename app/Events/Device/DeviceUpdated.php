<?php

namespace App\Events\Device;

use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class DeviceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Device $device)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['monitor', 'monitor.' . $this->device->id];
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'DeviceUpdated';
    }
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return ['name' => $this->device->name, 'ip' => $this->device->ip, 'type' => $this->device->type, 'loaded_page' => $this->device->loaded_page, 'settings' => $this->device->settings];
    }
}
