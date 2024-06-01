<?php

namespace App\Livewire\Livescores;

use Livewire\Component;
use App\Models\MatchDay;

class Index extends Component
{
    public $title = 'Livescores';
    public $matchdays;

    public function mount()
    {
        $this->matchdays = MatchDay::orderBy('date', 'desc')->with(['location', 'competition'])->get();
    }



    public function render()
    {
        return view('livewire.livescores.index')->layout('layouts.livescores');
    }
}
