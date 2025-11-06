<?php

namespace App\Livewire\Jury;

use App\Models\Score;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class ScoreInputForm extends Component
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
    public $b;
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
        $score = \App\Models\Score::where('match_day_id', $this->matchday)
            ->where('startnumber', $sn)
            ->where('toestel', $this->toestel)
            ->count();
        $this->d = '';
        $this->e1 = '';
        $this->e2 = '';
        $this->e3 = '';
        $this->e = '';
        $this->n = '';
        $this->t = '';
        if ($score == 0) {
            $this->startnumber = $sn;
            $this->locked = false;
        } else {
            $this->startnumber = null;
            $this->locked = true;
        }
    }

    public function calculate()
    {
        $this->d = $this->d ? (float)str_replace(',', '.', $this->d) : 0;
        $this->e1 = $this->e1 ? (float)str_replace(',', '.', $this->e1) : null;
        $this->e2 = $this->e2 ? (float)str_replace(',', '.', $this->e2) : null;
        $this->e3 = $this->e3 ? (float)str_replace(',', '.', $this->e3) : null;
        $this->n = $this->n ? (float)str_replace(',', '.', $this->n) : null;
        $this->b = $this->b ? (float)str_replace(',', '.', $this->b) : 0;
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        $this->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        if ($this->d == 0 || $this->d == '') {
            $this->n = 0;
            $this->t = 0;
            return;
        }
        // Delegate total calculation to Score model to keep logic in one place
        $tmp = new \App\Models\Score();
        $tmp->d = $this->d;
        $tmp->e1 = $this->e1;
        $tmp->e2 = $this->e2;
        $tmp->e3 = $this->e3;
        $tmp->n = $this->n;
        $tmp->b = $this->b ?? 0;
        $this->t = round($tmp->calculateTotal(), 3);
        if ($this->t < 0) {
            $this->t = 0;
        }
    }

    public function dns()
    {
        if (!$this->startnumber) return;

        $score = Score::updateOrCreate([
            'match_day_id' => $this->matchday,
            'startnumber' => $this->startnumber,
            'toestel' => $this->toestel
        ], ['d' => null]);

        if ($score->wasRecentlyCreated) {
            $this->dispatch('notification', 'Score invoer', $this->startnumber . ' is als DNS gemarkeerd', 'success');
        } else {
            $this->dispatch('notification', 'Score invoer', $this->startnumber . ' is al DNS gemarkeerd', 'warning');
        }

        $this->d = '';
        $this->e1 = '';
        $this->e2 = '';
        $this->e3 = '';
        $this->e = '';
        $this->n = '';
        $this->t = '';
        $this->startnumber = '';
        $this->locked = true;
    }

    public function save()
    {
        $this->calculate();
        if ($this->d < 0 || $this->d > 10) {
            $this->dispatch('notification', 'Score invoer', 'D score moet tussen 0 en 10 liggen', 'warning');
            return;
        }
        if ($this->e1 < 0 || $this->e1 > 10) {
            $this->dispatch('notification', 'Score invoer', 'E1 score moet tussen 0 en 10 liggen', 'warning');
            return;
        }
        if ($this->e2 < 0 || $this->e2 > 10) {
            $this->dispatch('notification', 'Score invoer', 'E2 score moet tussen 0 en 10 liggen', 'warning');
            return;
        }
        if ($this->e3 < 0 || $this->e3 > 10) {
            $this->dispatch('notification', 'Score invoer', 'E3 score moet tussen 0 en 10 liggen', 'warning');
            return;
        }
        if ($this->locked || empty($this->startnumber)) return;
        $score = Score::updateOrCreate([
            'match_day_id' => $this->matchday,
            'startnumber' => $this->startnumber,
            'toestel' => $this->toestel
        ], [
            'd' => $this->d,
            'e1' => $this->e1 == '' ? 0 : $this->e1,
            'e2' => $this->e2 == '' ? null : $this->e2,
            'e3' => $this->e3 == '' ? null : $this->e3,
            'n' => $this->n ?? 0,
            'b' => $this->b ?? 0,
            'total' => $this->t
        ]);
        if ($score->wasRecentlyCreated) {
            $this->dispatch('notification', 'Score invoer', 'Score opgeslagen', 'success');
        } else {
            $this->dispatch('notification', 'Score invoer', 'Score al opgeslagen', 'warning');
        }

        $this->d = '';
        $this->e1 = '';
        $this->e2 = '';
        $this->e3 = '';
        $this->e = '';
        $this->n = '';
        $this->b = '';
        $this->t = '';
        $this->startnumber = '';
        $this->locked = true;
    }

    public function render()
    {
        return view('livewire.jury.score-input-form');
    }
}
