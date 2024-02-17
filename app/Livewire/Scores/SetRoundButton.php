<?php

namespace App\Livewire\Scores;

use App\Models\Setting;
use Livewire\Component;

class SetRoundButton extends Component
{
    public $round;

    public function mount()
    {
        $this->round = (int)Setting::getValue('current_round');
    }

    public function setRound()
    {
        Setting::setValue('current_round', $this->round);
    }

    public function render()
    {
        return view('livewire.scores.set-round-button');
    }
}
