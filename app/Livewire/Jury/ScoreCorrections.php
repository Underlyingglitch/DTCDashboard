<?php

namespace App\Livewire\Jury;

use App\Models\User;
use App\Models\Score;
use Livewire\Component;
use App\Models\ScoreCorrection;

class ScoreCorrections extends Component
{
    public $matchday;
    public $corrections = [];

    public function getListeners()
    {
        return
            ['echo:jury,.ScoreCorrectionAdded' => 'hydrate'];
    }

    public function mount($matchday)
    {
        $this->matchday = $matchday->id;
        $this->hydrate();
    }

    public function hydrate()
    {
        $corrections = ScoreCorrection::where('approved', false)
            ->whereHas('score', function ($query) {
                $query->where('match_day_id', $this->matchday);
            })
            ->get();
        foreach ($corrections as $correction) {
            $this->corrections[$correction->id] = $correction;
        }
    }

    public function delete($correction)
    {
        $sc = ScoreCorrection::find($correction);
        $sc->delete();
        unset($this->corrections[$correction]);
    }

    public function approve($correction)
    {
        $sc = ScoreCorrection::find($correction);
        $sc->approve();
        unset($this->corrections[$correction]);
    }

    public function render()
    {
        return view('livewire.jury.score-corrections');
    }
}
