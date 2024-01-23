<?php

namespace App\Livewire\Wedstrijd;

use Livewire\Component;
use Livewire\Attributes\On;

class TeamTableItem extends Component
{
    public $registration;
    public $wedstrijd_baans;
    public $teams;
    public $team;

    public function mount($registration, $wedstrijd, $wedstrijd_baans)
    {
        $this->registration = $registration;
        $this->wedstrijd_baans = $wedstrijd_baans;
        $this->team = $registration->team == null ? '--' : $registration->team->id;
        $this->teams = $wedstrijd->teams;
    }

    public function updated($team)
    {
        $this->registration->update(['team_id' => $this->team]);
    }

    #[On('registration{registration.id}Updated')]
    public function render()
    {
        return view('livewire.wedstrijd.team-table-item');
    }
}
