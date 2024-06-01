<?php

namespace App\Livewire\Livescores;

use Livewire\Component;
use App\Models\MatchDay;

class MatchDayView extends Component
{
    public $matchday;
    public $page = 'individual';
    public $niveau;
    public $teams = false;

    public $title = '';

    public $niveaus;

    public function mount(MatchDay $matchday, $niveau = null)
    {
        if (is_null($niveau)) {
            $niveau = $matchday->niveaus->first()->id;
        }
        $this->title = $matchday->name . ' - ' . $matchday->date->format('d-m-Y');
        $this->matchday = $matchday->id;
        $this->niveau = $niveau;
        $teams = $matchday->competition->teams->where('niveau_id', $niveau);
        if ($teams->count() > 0) {
            $this->teams = true;
        }
        $this->niveaus = $matchday->niveaus->sortBy('order');
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
        return view('livewire.livescores.matchday')->layout('layouts.livescores');
    }
}
