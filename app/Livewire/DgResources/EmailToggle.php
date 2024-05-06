<?php

namespace App\Livewire\DgResources;

use Livewire\Component;

class EmailToggle extends Component
{
    public $class = 'btn-success';
    public $value = 'Aan';

    public function mount()
    {
        $this->value = \App\Models\UserSetting::getValue('dg_resources_subscribed', false) ? 'Aan' : 'Uit';
        $this->class = $this->value == 'Aan' ? 'btn-success' : 'btn-danger';
    }

    public function toggle()
    {
        $this->value = $this->value == 'Aan' ? 'Uit' : 'Aan';
        $this->class = $this->value == 'Aan' ? 'btn-success' : 'btn-danger';
        \App\Models\UserSetting::setValue('dg_resources_subscribed', $this->value == 'Aan');
    }

    public function render()
    {
        return view('livewire.dg-resources.email-toggle');
    }
}
