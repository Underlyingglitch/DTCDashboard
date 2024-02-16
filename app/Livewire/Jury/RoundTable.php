<?php

namespace App\Livewire\Jury;

use App\Models\Setting;
use Livewire\Component;

class RoundTable extends Component
{
    public $matchday;
    public $toestel;
    public $current_round;

    public function getListeners()
    {
        return [
            "echo:jury,.RoundUpdated" => 'updateRound',
        ];
    }

    public function updateRound($data)
    {
        $this->current_round = $data['round'];
    }

    public function mount()
    {
        $this->matchday = Setting::getValue('current_match_day');
        $this->current_round = Setting::getValue('current_round');
    }

    public function render()
    {
        return view('livewire.jury.round-table');
    }
}
