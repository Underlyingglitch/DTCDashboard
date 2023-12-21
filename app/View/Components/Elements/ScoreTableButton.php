<?php

namespace App\View\Components\Elements;

use App\Models\ProcessedScore;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ScoreTableButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $class = "btn-danger";
    public $href = "#";
    public function __construct(
        public $wedstrijd,
        public $groupnr = null,
        public $toestel,
        public $pc
    ) {
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

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.score-table-button');
    }
}
