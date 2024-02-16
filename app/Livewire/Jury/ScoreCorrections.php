<?php

namespace App\Livewire\Jury;

use App\Models\Score;
use Livewire\Component;
use App\Models\ScoreCorrection;

class ScoreCorrections extends Component
{
    public $matchday;
    public $corrections;

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
        $this->corrections = ScoreCorrection::where('approved', false)
            ->whereHas('score', function ($query) {
                $query->where('match_day_id', $this->matchday);
            })
            ->get();
        // dd($this->corrections);

    }

    public function delete($correction)
    {
        ScoreCorrection::find($correction)->delete();
        $this->hydrate();
    }

    public function approve($correction)
    {
        $sc = ScoreCorrection::find($correction);
        $sc->update([
            'approved' => true
        ]);
        $score = Score::find($sc->score_id);
        $score->update([
            'd' => $sc->d,
            'e1' => $sc->e1,
            'e2' => $sc->e2,
            'e3' => $sc->e3,
            'n' => $sc->n,
            'total' => $sc->total
        ]);
        $this->hydrate();
    }

    public function render()
    {
        return view('livewire.jury.score-corrections');
    }
}
