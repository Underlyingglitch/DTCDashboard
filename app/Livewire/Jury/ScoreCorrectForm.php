<?php

namespace App\Livewire\Jury;

use Livewire\Component;

class ScoreCorrectForm extends Component
{
    public $startnumber;
    public $toestel;
    public $matchday;

    public function mount($toestel, $matchday)
    {
        $this->toestel = $toestel;
        $this->matchday = $matchday->id;
    }

    public function render()
    {
        return view('livewire.jury.score-correct-form');
    }
}
