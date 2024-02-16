<?php

namespace App\Livewire\Jury;

use Livewire\Component;

class ScoreCorrectionEnabledButton extends Component
{
    public $enabled = false;

    public function mount()
    {
        $this->enabled = \App\Models\Setting::getValue('score_correction_enabled');
    }

    public function toggle()
    {
        $this->enabled = !$this->enabled;
        \App\Models\Setting::setValue('score_correction_enabled', $this->enabled);
    }

    public function render()
    {
        return view('livewire.jury.score-correction-enabled-button');
    }
}
