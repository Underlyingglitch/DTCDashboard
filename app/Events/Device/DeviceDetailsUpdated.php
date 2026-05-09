<?php

namespace App\Events\Device;

use App\Models\Device;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class DeviceDetailsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Device $device,
        public array $details
    ) {}

    /**
     * Get the channels the event should broadcast on.
     * Broadcast to the monitor channel and device-specific channel.
     */
    public function broadcastOn()
    {
        return ['monitor', 'monitor.' . $this->device->id];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'DeviceDetailsUpdated';
    }

    /**
     * The event's broadcast data.
     */
    public function broadcastWith()
    {
        return [
            'device_id' => $this->device->id,
            'device_name' => $this->device->device_id,
            'details' => $this->details,
            'updated_at' => now(),
        ];
    }
}
