<?php

namespace App\Livewire\Monitor;

use App\Models\Device;
use Livewire\Component;

class RegisteredDevices extends Component
{
    public $devices = [];
    public $select_devices = [];

    public $deviceSelect;
    public $code;

    protected $listeners = [
        'echo:monitor,.DeviceUpdated' => 'updatedDevice',
        'echo:monitor,.DeviceRegistered' => 'addDevice',
    ];

    public function mount()
    {
        $this->select_devices = Device::where('type', '!=', 'registered')->select('id', 'name')->get()->toArray();
        $this->devices = Device::where('type', 'registered')->pluck('device_id', 'name')->toArray();
    }

    public function deleteRegistration($code)
    {
        Device::where('name', $code)->delete();
        unset($this->devices[$code]);
    }

    public function addDevice($payload)
    {
        $this->devices[$payload['name']] = $payload['device_id'];
    }

    public function updatedDevice($payload)
    {
        foreach ($this->devices as $code => $device_id) {
            if ($device_id == $payload['device_id']) {
                unset($this->devices[$code]);
            }
        }
    }

    public function render()
    {
        return view('livewire.monitor.registered-devices');
    }
}
