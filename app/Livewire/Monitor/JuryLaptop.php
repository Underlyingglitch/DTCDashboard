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

    public $laptop;

    public $code;
    public $selected_page;

    protected $listeners = ['deviceUpdated' => 'handleDeviceUpdated'];

    public function mount($laptop)
    {
        $this->laptop = $laptop;
        $this->selected_page = $laptop['loaded_page'];
    }

    public function handleDeviceUpdated($payload)
    {
        // Handle the event
        if ($this->laptop['id'] == $payload['id']) {
            // Perform actions based on the event
            $this->laptop = $payload;
            $this->selected_page = $payload['loaded_page'];
        }
    }

    public function removeDevice()
    {
        $device = \App\Models\Device::find($this->laptop['id']);
        $device->update(['device_id' => null]);
    }

    public function assignDevice()
    {
        $registered_device = Device::where('name', $this->code)->first();
        $device = Device::find($this->laptop['id']);
        $device->device_id = $registered_device->device_id;
        $device->loaded_page = '/auth/login_as';
        $device->save();
        $registered_device->delete();
        $this->code = null;
    }

    public function setPage()
    {
        if (!array_key_exists($this->selected_page, $this->pages)) return;
        $device = Device::find($this->laptop['id']);
        $device->user->update(['locked' => false]);
        $device->update(['loaded_page' => $this->selected_page]);
    }

    public function render()
    {
        return view('livewire.monitor.jury-laptop');
    }
}
