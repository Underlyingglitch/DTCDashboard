<?php

namespace App\Livewire\Monitor;

use App\Models\Device;
use Livewire\Component;

class JuryLaptops extends Component
{
    public $devices = [];

    public $listeners = [
        'echo:monitor,.DeviceUpdated' => 'deviceUpdated',
        'echo:monitor,.DeviceDetailsUpdated' => 'deviceDetailsUpdated'
    ];

    public function mount()
    {
        $this->devices = Device::where('type', 'jury')->get()->toArray();
    }

    public function deviceUpdated($payload)
    {
        // Trigger event on the child livewire component 
        $this->dispatch('deviceUpdated', $payload);
    }

    public function deviceDetailsUpdated($payload)
    {
        // Trigger event on the child livewire component for real-time updates
        $this->dispatch('deviceDetailsUpdated', $payload);
    }

    public function render()
    {
        return view('livewire.monitor.jury-laptops');
    }
}
