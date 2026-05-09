<?php

return [
    'host' => env('MQTT_HOST', 'mqtt'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', 'mosquitto'),
    'password' => env('MQTT_PASSWORD', 'mosquitto'),
    'protocol' => env('MQTT_PROTOCOL', 'mqtt'),
    'client_id' => env('MQTT_CLIENT_ID', 'laravel_mqtt_client'),
];
