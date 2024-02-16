<?php

namespace App\Livewire\Jury;

use Livewire\Component;
use App\Models\ScoreCorrection;
use Illuminate\Support\Facades\Auth;

class ScoreCorrectForm extends Component
{
    public $startnumber;
    public $toestel;
    public $jury = true;
    public $matchday;
    public $locked = true;
    public $score_id;
    public $d;
    public $e;
    public $e1;
    public $e2;
    public $e3;
    public $n;
    public $t;
    public $delete = false;

    public function mount($toestel, $matchday)
    {
        $this->toestel = $toestel;
        if ($toestel == null) {
            $this->jury = false;
            $this->toestel = 1;
        }
        $this->matchday = $matchday->id;
    }

    public function sn_updated()
    {
        $score = \App\Models\Score::where('match_day_id', $this->matchday)
            ->where('startnumber', $this->startnumber)
            ->where('toestel', $this->toestel)
            ->first();
        if ($score) {
            $this->score_id = $score->id;
            $this->d = $score->d;
            $this->e1 = $score->e1;
            $this->e2 = $score->e2;
            $this->e3 = $score->e3;
            $this->e = $score->e;
            $this->n = $score->n;
            $this->t = $score->total;
            $this->locked = false;
        } else {
            $this->d = '';
            $this->e1 = '';
            $this->e2 = '';
            $this->e3 = '';
            $this->e = '';
            $this->n = '';
            $this->t = '';
            $this->locked = true;
        }
        $this->delete = false;
    }

    public function calculate()
    {
        if ($this->locked) return;
        $this->delete = false;
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        $this->e = count($es) > 0 ? array_sum($es) / count($es) : null;
        if ($this->d == 0) {
            $this->t = 0;
            return;
        }
        $this->t = 10 - $this->e + $this->d - $this->n;
        if ($this->t < 0) {
            $this->t = 0;
        }
        // Round to 3 decimals
        $this->t = round($this->t, 3);
    }

    public function save()
    {
        if ($this->locked) return;
        if (empty($this->e1)) {
            Auth::user()->notifyNow(new \App\Notifications\UserNotification('Score correctie', 'E1 score mag niet leeg zijn', 'warning'));
            return;
        }
        if ($this->d == 0 && !$this->delete) {
            $this->delete = true;
            Auth::user()->notifyNow(new \App\Notifications\UserNotification('Score correctie', 'D score 0 zal deze score in zijn geheel verwijderen. Druk nogmaals op opslaan om te bevestigen', 'info'));
            return;
        }
        $sc = ScoreCorrection::create([
            'score_id' => $this->score_id,
            'd' => $this->d,
            'e1' => $this->e1,
            'e2' => $this->e2,
            'e3' => $this->e3,
            'n' => $this->n,
            'total' => $this->t
        ]);
        event(new \App\Events\Jury\ScoreCorrectionAdded($sc));
        if ($this->jury) {
            if ($this->d == 0) {
                Auth::user()->notifyNow(new \App\Notifications\UserNotification('Score correctie', 'Score correctie succesvol verwijderd', 'success'));
            } else {
                Auth::user()->notifyNow(new \App\Notifications\UserNotification('Score correctie', 'Score correctie succesvol opgeslagen', 'success'));
            }
        }
        $this->d = '';
        $this->e1 = '';
        $this->e2 = '';
        $this->e3 = '';
        $this->e = '';
        $this->n = '';
        $this->t = '';
        $this->startnumber = '';
        $this->delete = false;
        $this->locked = true;
        $this->toestel = $this->jury ? $this->toestel : null;
    }

    public function render()
    {
        return view('livewire.jury.score-correct-form');
    }
}
