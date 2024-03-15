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
        $user_id = $sc->user_id;
        $sc->update([
            'approved' => true
        ]);
        $score = Score::find($sc->score_id);
        $startnumber = $score->startnumber;
        if ($sc->d == 0) {
            $score->delete();
        } else {
            $score->update([
                'd' => $sc->d,
                'e1' => $sc->e1,
                'e2' => $sc->e2,
                'e3' => $sc->e3,
                'n' => $sc->n,
                'total' => $sc->total
            ]);
        }
        unset($this->corrections[$correction]);
    }

    public function render()
    {
        return view('livewire.jury.score-corrections');
    }
}
