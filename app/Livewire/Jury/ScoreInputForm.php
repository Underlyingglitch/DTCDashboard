<?php

namespace App\Livewire\Jury;

use App\Models\Score;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ScoreInputForm extends Component
{
    public $startnumber;
    public $toestel;
    public $matchday;
    public $locked = false;
    public $d;
    public $e;
    public $e1;
    public $e2;
    public $e3;
    public $n;
    public $t;

    public function mount($toestel, $matchday)
    {
        $this->toestel = $toestel;
        $this->matchday = $matchday->id;
    }

    #[On('sn_clicked')]
    public function sn_clicked($sn)
    {
        $this->startnumber = $sn;
        $score = \App\Models\Score::where('match_day_id', $this->matchday)
            ->where('startnumber', $this->startnumber)
            ->where('toestel', $this->toestel)
            ->first();
        if ($score) {
            $this->d = $score->d;
            $this->e1 = $score->e1;
            $this->e2 = $score->e2;
            $this->e3 = $score->e3;
            $this->e = $score->e;
            $this->n = $score->n;
            $this->t = $score->total;
            $this->locked = true;
        } else {
            $this->d = '';
            $this->e1 = '';
            $this->e2 = '';
            $this->e3 = '';
            $this->e = '';
            $this->n = '';
            $this->t = '';
            $this->locked = false;
        }
    }

    public function calculate()
    {
        $this->d = $this->d ? (float)str_replace(',', '.', $this->d) : null;
        $this->e1 = $this->e1 ? (float)str_replace(',', '.', $this->e1) : null;
        $this->e2 = $this->e2 ? (float)str_replace(',', '.', $this->e2) : null;
        $this->e3 = $this->e3 ? (float)str_replace(',', '.', $this->e3) : null;
        $this->n = $this->n ? (float)str_replace(',', '.', $this->n) : null;
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        $this->e = count($es) > 0 ? round(array_sum($es) / count($es), 1) : null;
        if ($this->d == 0 || $this->d == '') {
            $this->t = 0;
            return;
        }
        if ($this->n == '') $this->n = 0;
        $this->t = 10 - $this->e + $this->d - $this->n;
        if ($this->t < 0) {
            $this->t = 0;
        }
        // Round to 3 decimals
        $this->t = round($this->t, 3);
    }

    public function save()
    {
        $this->calculate();
        if ($this->locked || empty($this->startnumber)) return;
        if (empty($this->e1) && !empty($this->d)) {
            Auth::user()->notifyNow(new \App\Notifications\UserNotification('Score invoer', 'E1 score mag niet leeg zijn', 'warning'));
            return;
        }
        $score = Score::create([
            'match_day_id' => $this->matchday,
            'startnumber' => $this->startnumber,
            'toestel' => $this->toestel,
            'd' => $this->d,
            'e1' => $this->e1 == '' ? 0 : $this->e1,
            'e2' => $this->e2 == '' ? null : $this->e2,
            'e3' => $this->e3 == '' ? null : $this->e3,
            'n' => $this->n,
            'total' => $this->t
        ]);
        event(new \App\Events\Scores\ScoreUpdated($this->matchday, $score));
        $this->d = '';
        $this->e1 = '';
        $this->e2 = '';
        $this->e3 = '';
        $this->e = '';
        $this->n = '';
        $this->t = '';
        $this->startnumber = '';
        $this->locked = false;
    }

    public function render()
    {
        return view('livewire.jury.score-input-form');
    }
}
