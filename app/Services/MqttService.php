<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected MqttClient $client;

    public function __construct()
    {
        $this->client = new MqttClient(
            config('mqtt.host'),
            config('mqtt.port'),
            config('mqtt.client_id')
        );
    }

    /**
     * Publish a message to an MQTT topic.
     */
    public function publish(string $topic, string $message, int $qos = 1): bool
    {
        try {
            if (!$this->client->isConnected()) {
                $this->connect();
            }

            $this->client->publish($topic, $message, $qos);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to publish to MQTT topic {$topic}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Connect to MQTT broker.
     */
    protected function connect(): void
    {
        $connectionSettings = (new ConnectionSettings())
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(3)
            ->setUseTls(false);

        $this->client->connect($connectionSettings);
    }

    /**
     * Disconnect from MQTT broker.
     */
    public function disconnect(): void
    {
        if ($this->client->isConnected()) {
            $this->client->disconnect();
        }
    }

    /**
     * Subscribe to an MQTT topic with a callback.
     */
    public function subscribe(string $topic, callable $callback, int $qos = 1): void
    {
        if (!$this->client->isConnected()) {
            $this->connect();
        }

        $this->client->subscribe($topic, $callback, $qos);
    }

    /**
     * Start listening for messages (blocking operation).
     */
    public function loop(bool $blocking = true): void
    {
        $this->client->loop($blocking);
    }

    /**
     * Get the MQTT client instance.
     */
    public function getClient(): MqttClient
    {
        return $this->client;
    }
}
