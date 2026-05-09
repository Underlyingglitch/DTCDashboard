<?php

namespace App\Console\Commands;

use App\Events\Device\DeviceDetailsUpdated;
use App\Models\Device;
use App\Services\MqttService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ListenMqttTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to MQTT topics for device updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting MQTT listener...');

        try {
            $mqtt = new MqttService();

            $this->info('Connected to MQTT broker');

            // Subscribe to topics
            $mqtt->subscribe('jurypanel/+/connection', function (string $topic, string $message) {
                Log::info("Received MQTT message on topic {$topic}: {$message}");
                $this->handleConnectionMessage($topic, $message);
            }, 1);

            $mqtt->subscribe('jurypanel/+/state', function (string $topic, string $message) {
                Log::info("Received MQTT message on topic {$topic}: {$message}");
                $this->handleStateMessage($topic, $message);
            }, 1);

            $mqtt->subscribe('jurypanel/+/sensor/battery', function (string $topic, string $message) {
                Log::info("Received MQTT message on topic {$topic}: {$message}");
                $this->handleBatteryMessage($topic, $message);
            }, 1);

            $this->info('Subscribed to device topics');

            // Listen continuously
            $mqtt->loop(true);
        } catch (\Exception $e) {
            $this->error('MQTT Error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Handle connection status messages.
     * Topic: jurypanel/{devicename}/connection
     * Message: 'online' or 'offline'
     */
    protected function handleConnectionMessage(string $topic, string $message)
    {
        $deviceName = $this->extractDeviceNameFromTopic($topic);
        $isOnline = $message === 'online';

        $device = Device::where('device_id', $deviceName)->first();

        if ($device) {
            $device->update(['is_online' => $isOnline]);

            event(new DeviceDetailsUpdated($device, [
                'is_online' => $isOnline,
                'type' => 'connection',
            ]));

            $this->info("Device {$deviceName} is {$message}");
        }
    }

    /**
     * Handle device state messages.
     * Topic: jurypanel/{devicename}/state
     * Message: JSON {"currentUrl": string, "screenOn": bool, "camera": bool, "brightness": int}
     */
    protected function handleStateMessage(string $topic, string $message)
    {
        $deviceName = $this->extractDeviceNameFromTopic($topic);

        $state = json_decode($message, true);

        if (!is_array($state)) {
            $this->error("Invalid state message for device {$deviceName}: {$message}");
            return;
        }

        $device = Device::where('device_id', $deviceName)->first();

        if ($device) {
            $device->update(['current_state' => $state]);

            event(new DeviceDetailsUpdated($device, [
                'current_state' => $state,
                'type' => 'state',
            ]));

            $this->info("Device {$deviceName} state updated: " . json_encode($state));
        }
    }

    /**
     * Handle battery status messages.
     * Topic: jurypanel/{devicename}/sensor/battery
     * Message: JSON {"value": int, "unit": "%", "charging": bool}
     */
    protected function handleBatteryMessage(string $topic, string $message)
    {
        $deviceName = $this->extractDeviceNameFromTopic($topic);

        $battery = json_decode($message, true);

        if (!is_array($battery)) {
            $this->error("Invalid battery message for device {$deviceName}: {$message}");
            return;
        }

        $device = Device::where('device_id', $deviceName)->first();

        if ($device) {
            $device->update(['battery' => $battery]);

            event(new DeviceDetailsUpdated($device, [
                'battery' => $battery,
                'type' => 'battery',
            ]));

            $this->info("Device {$deviceName} battery updated: {$battery['value']}{$battery['unit']} (charging: " . ($battery['charging'] ? 'yes' : 'no') . ")");
        }
    }

    /**
     * Extract device name from MQTT topic.
     * Topic format: jurypanel/{devicename}/...
     */
    protected function extractDeviceNameFromTopic(string $topic): string
    {
        $parts = explode('/', $topic);
        return $parts[1] ?? '';
    }
}
