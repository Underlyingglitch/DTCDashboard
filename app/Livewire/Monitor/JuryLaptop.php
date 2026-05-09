<?php

namespace App\Livewire\Monitor;

use App\Models\Device;
use Livewire\Component;

class JuryLaptop extends Component
{
    public $pages = [
        '/jurytafel' => 'Jury index',
        '/jurytafel/1' => 'Vloer',
        '/jurytafel/2' => 'Voltige',
        '/jurytafel/3' => 'Ringen',
        '/jurytafel/4' => 'Sprong',
        '/jurytafel/5' => 'Brug',
        '/jurytafel/6' => 'Rekstok',
        '/auth/login' => 'Inloggen',
        '/auth/logout' => 'Uitloggen',
        '/auth/lock' => 'Vergrendelen',
        '/auth/login_as' => 'Ontgrendelen',
    ];

    public $device;
    public $code;
    public $selected_page;
    public $battery = null;
    public $isOnline = false;
    public $currentState = [];
    public $authToken = null;

    protected $listeners = [
        'deviceUpdated' => 'handleDeviceUpdated',
        'echo:monitor,.DeviceDetailsUpdated' => 'handleDeviceDetailsUpdated'
    ];

    public function mount($device)
    {
        $this->device = $device;
        $this->selected_page = $device['loaded_page'];
        $this->loadDeviceStatus();
    }

    protected function loadDeviceStatus()
    {
        $device = Device::find($this->device['id']);

        if ($device) {
            $this->isOnline = $device->is_online;
            $this->battery = $device->battery;
            $this->currentState = $device->current_state ?? [];
        }
    }

    public function handleDeviceUpdated($payload)
    {
        // Handle the event
        if ($this->device['id'] == $payload['id']) {
            $this->device = $payload;
            $this->selected_page = $payload['loaded_page'];
        }
    }

    public function handleDeviceDetailsUpdated($payload)
    {
        // Handle real-time device details updates
        if ($this->device['id'] == $payload['device_id']) {
            $this->loadDeviceStatus();
        }
    }

    public function removeDevice()
    {
        $device = Device::find($this->device['id']);
        $device->update(['device_id' => null]);
    }

    public function assignDevice()
    {
        $registered_device = Device::where('name', $this->code)->first();
        $device = Device::find($this->device['id']);
        $device->device_id = $registered_device->device_id;
        $device->loaded_page = '/jurytafel';
        $device->save();
        $registered_device->delete();
        $this->code = null;
        $this->device = $device->toArray();
    }

    public function setPage()
    {
        if (!array_key_exists($this->selected_page, $this->pages)) return;
        $device = Device::find($this->device['id']);
        $device->update(['loaded_page' => $this->selected_page]);
    }

    public function sendAuthToken()
    {
        $device = Device::find($this->device['id']);

        try {
            $controller = app(\App\Http\Controllers\DeviceAuthController::class);
            $request = request();
            $request->merge(['device_id' => $device->id]);

            $controller->sendTokenToDevice($request);
            $this->dispatch('notification', ['message' => 'Authentication token sent to device', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('notification', ['message' => 'Failed to send token: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        return view('livewire.monitor.jury-laptop');
    }
}
