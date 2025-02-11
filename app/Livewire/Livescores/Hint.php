<?php

namespace App\Livewire\Livescores;

use Livewire\Component;

class Hint extends Component
{
    public $shown = false;

    public function mount()
    {
        $this->shown = !\App\Models\UserSetting::getValue('livescores_hint_hidden', false);
    }

    public function hide()
    {
        \App\Models\UserSetting::setValue('livescores_hint_hidden', true);
        $this->shown = false;
    }

    public function render()
    {
        return view('livewire.livescores.hint');
    }
}
