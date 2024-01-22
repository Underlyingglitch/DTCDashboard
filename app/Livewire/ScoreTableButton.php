<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ScoreTableButton extends Component
{
    public $wedstrijd;
    public $pc;
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
        $groupnr = null,
        $toestel,
        $pc
    ) {
        $this->groupnr = $groupnr;
        $this->toestel = $toestel;
        $this->wedstrijd = $wedstrijd;
        $this->pc = $pc;

        if ($this->toestel > 6) {
            $this->class = "btn-secondary";
            return;
        }
        if (is_null($this->groupnr)) {
            $this->class = "btn-secondary";
        } else {
            $pc = $this->pc
                ->where('group_id', $this->groupnr)
                ->where('toestel', $this->toestel)
                ->first();
            if ($pc->completed ?? null) {
                $this->class = "btn-success";
            } else {
                $this->class = $pc ? "btn-warning" : "btn-danger";
                $this->href = route('wedstrijden.score.add', [
                    'wedstrijd' => $this->wedstrijd,
                    'toestel' => $this->toestel,
                    'group' => $this->groupnr
                ]);
            }
        }
    }

    public function update($data)
    {
        Log::info('Update method called with data: ', $data);
        if ($data['wedstrijd_id'] != $this->wedstrijd || $data['toestel'] != $this->toestel || $data['groupnr'] != $this->groupnr) {
            return;
        }
        if ($data['completed']) {
            $this->class = "btn-success";
            $this->href = "#";
        } else {
            $this->class = "btn-warning";
        }
    }

    public function render()
    {
        return view('livewire.score-table-button');
    }
}
