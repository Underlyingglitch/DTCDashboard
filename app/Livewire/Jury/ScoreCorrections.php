<?php

namespace App\Livewire\Jury;

use Livewire\Component;

class ScoreCorrections extends Component
{
    public $matchday;
    public $corrections;

    public function mount($matchday)
    {
        $this->matchday = $matchday->id;
        $this->corrections = \App\Models\ScoreCorrection::with(['score' => function ($query) {
            $query->where('match_day_id', $this->matchday);
        }])->get();
    }

    public function render()
    {
        return view('livewire.jury.score-corrections');
    }
}
