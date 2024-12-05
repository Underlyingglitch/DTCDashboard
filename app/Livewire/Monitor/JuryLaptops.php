<?php

namespace App\Livewire\Monitor;

use App\Models\Device;
use Livewire\Component;

class JuryLaptops extends Component
{
    public $jury_laptops = [];

    public $listeners = ['echo:monitor,.DeviceUpdated' => 'deviceUpdated'];

    public function mount()
    {
        $this->jury_laptops = Device::where('type', 'jury')->get()->toArray();
    }

    public function deviceUpdated($payload)
    {
        // Trigger event on the child livewire component 
        $this->dispatch('deviceUpdated', $payload);
    }

    public function render()
    {
        return view('livewire.monitor.jury-laptops');
    }
}
