<?php

namespace App\Livewire\Jury;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ScoreCorrection;
use Illuminate\Support\Facades\Log;
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

    public function getListeners()
    {
        return [
            "echo:settings.score_correction_enabled,.SettingUpdated" => 'render',
        ];
    }

    public function mount($toestel, $matchday)
    {
        $this->toestel = $toestel;
        if ($toestel == null) {
            $this->jury = false;
            $this->toestel = 1;
        }
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
        if ($score != 0) {
            $this->startnumber = $sn;
            $this->sn_updated();
        } else {
            $this->startnumber = null;
        }
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
    }

    public function calculate()
    {
        $this->d = $this->d ? (float)str_replace(',', '.', $this->d) : null;
        $this->e1 = $this->e1 ? (float)str_replace(',', '.', $this->e1) : null;
        $this->e2 = $this->e2 ? (float)str_replace(',', '.', $this->e2) : null;
        $this->e3 = $this->e3 ? (float)str_replace(',', '.', $this->e3) : null;
        $this->n = $this->n ? (float)str_replace(',', '.', $this->n) : null;
        if ($this->locked) return;
        $es = array_filter([$this->e1, $this->e2, $this->e3]);
        $this->e = count($es) > 0 ? round(array_sum($es) / count($es), 3) : null;
        if ($this->d == 0) {
            $this->t = 0;
            $this->e1 = '';
            $this->e2 = '';
            $this->e3 = '';
            $this->e = '';
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

        $this->calculate();

        if ($this->d == 0) {
            $this->d = 0;
            $this->e1 = 0;
            $this->e2 = 0;
            $this->e3 = 0;
            $this->e = 0;
            $this->n = 0;
            $this->t = 0;
        } else {
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
            if (empty($this->e1)) {
                $this->dispatch('notification', 'Score correctie', 'E1 score mag niet leeg zijn', 'warning');
                return;
            }
        }
        $sc = ScoreCorrection::updateOrCreate(
            [
                'score_id' => $this->score_id
            ],
            [
                'startnumber' => $this->startnumber,
                'd' => $this->d,
                'e1' => $this->e1,
                'e2' => $this->e2,
                'e3' => $this->e3,
                'e' => $this->e,
                'n' => $this->n ?? 0,
                'total' => $this->t,
                'approved' => false,
                'user_id' => Auth::user()->id
            ]
        );
        if ($this->jury) {
            if ($this->d == 0) {
                $this->dispatch('notification', 'Score correctie', 'Score succesvol verwijderd', 'success');
            } else {
                $this->dispatch('notification', 'Score correctie', 'Score correctie succesvol opgeslagen', 'success');
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
        $this->locked = true;
        $this->toestel = $this->jury ? $this->toestel : null;
    }

    public function render()
    {
        if (Setting::getValue('current_match_day') != $this->matchday) {
            return view('livewire.jury.score-correct-form-disabled');
        }
        if (!Setting::getValue('score_correction_enabled') && $this->jury) {
            return view('livewire.jury.score-correct-form-disabled');
        }
        return view('livewire.jury.score-correct-form');
    }
}
