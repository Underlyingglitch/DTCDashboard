<?php

namespace App\Livewire\Livescores;

use Livewire\Component;
use App\Models\MatchDay;

class Index extends Component
{
    public $matchday;
    public $page = 'individual';
    public $niveau;
    public $teams = false;

    public $niveaus;

    public function mount(MatchDay $matchday, $niveau = null)
    {
        if (is_null($niveau)) {
            $niveau = $matchday->niveaus->first()->id;
        }
        $this->matchday = $matchday->id;
        $this->niveau = $niveau;
        $teams = $matchday->competition->teams->where('niveau_id', $niveau);
        if ($teams->count() > 0) {
            $this->teams = true;
        }
        $this->niveaus = $matchday->niveaus;
    }

    public function tab($page)
    {
        $this->page = $page;
    }

    public function setNiveau($n)
    {
        $this->niveau = $n;
        if (MatchDay::find($this->matchday)->competition->teams->where('niveau_id', $n)->count() > 0) {
            $this->teams = true;
        } else {
            $this->teams = false;
            $this->page = 'individual';
        }
    }

    public function render()
    {
        return view('livewire.livescores.index')->layout('layouts.livescores');
    }
}
