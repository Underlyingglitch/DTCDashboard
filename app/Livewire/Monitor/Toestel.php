<?php

namespace App\Livewire\Monitor;

use Cache;
use Livewire\Component;

class Toestel extends Component
{
    public $toestel;
    public $count;

    public function getListeners()
    {
        return [
            "echo:monitor.jurytafel.{$this->toestel},.JuryTafelPresenceChanged" => 'updateCount',
        ];
    }

    public function mount($toestel)
    {
        $this->toestel = $toestel;
        $this->count = Cache::get('monitor.jurytafel.' . $toestel, 0);
    }

    public function updateCount($data)
    {
        $this->count = $data['count'];
    }

    public function render()
    {
        return view('livewire.monitor.toestel');
    }
}
