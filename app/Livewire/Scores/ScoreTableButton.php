<?php

namespace App\Livewire\Scores;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ScoreTableButton extends Component
{
    public $wedstrijd;
    public $pss;
    public $groupnr;
    public $toestel;
    public $class = "btn-danger";
    public $href = "#";

    public function getListeners()
    {
        return
            ['echo:scorepage.' . $this->wedstrijd . '.' . $this->toestel . '.' . $this->groupnr . ',.ProcessedScoreUpdated' => 'update'];
    }

    public function mount(
        $wedstrijd,
        $toestel,
        $pss,
        $groupnr = 0
    ) {
        $this->groupnr = $groupnr;
        $this->toestel = $toestel;
        $this->wedstrijd = $wedstrijd;
        $this->pss = $pss;

        if ($this->toestel > 6) {
            $this->class = "btn-secondary";
            return;
        }
        if ($this->groupnr == 0) {
            $this->class = "btn-secondary";
            $this->groupnr = null;
        } else {
            $ps = $this->pss
                ->where('group_id', $this->groupnr)
                ->where('toestel', $this->toestel)
                ->first();
            if ($ps->completed ?? null) {
                $this->class = "btn-success";
            } else {
                $this->class = $ps ? "btn-warning" : "btn-danger";
            }
            $this->href = route('wedstrijden.score.add', [
                'wedstrijd' => $this->wedstrijd,
                'toestel' => $this->toestel,
                'group' => $this->groupnr
            ]);
        }
    }

    public function update($data)
    {
        if ($data['wedstrijd_id'] != $this->wedstrijd || $data['toestel'] != $this->toestel || $data['groupnr'] != $this->groupnr) {
            return;
        }
        if ($data['deleted']) {
            $this->class = "btn-danger";
            return;
        }
        if ($data['completed']) {
            $this->class = "btn-success";
        } else {
            $this->class = "btn-warning";
        }
    }

    public function render()
    {
        return view('livewire.scores.score-table-button');
    }
}
