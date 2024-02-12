<?php

namespace App\Livewire\Jury;

use Livewire\Component;

class ScoreCorrectForm extends Component
{
    public $startnumber;
    public $toestel;
    public $matchday;
    public $locked = true;
    public $d;
    public $e;
    public $e1;
    public $e2;
    public $e3;
    public $n;

    public function mount($toestel, $matchday)
    {
        $this->toestel = $toestel;
        $this->matchday = $matchday->id;
    }

    public function sn_updated()
    {
        $score = \App\Models\Score::where('match_day_id', $this->matchday)
            ->where('startnumber', $this->startnumber)
            ->where('toestel', $this->toestel)
            ->first();
        if ($score) {
            $this->d = $score->d;
            $this->e = $score->e;
            $this->n = $score->n;
            $this->locked = false;
        } else {
            $this->d = '';
            $this->e = '';
            $this->n = '';
            $this->locked = true;
        }
    }

    public function render()
    {
        return view('livewire.jury.score-correct-form');
    }
}
