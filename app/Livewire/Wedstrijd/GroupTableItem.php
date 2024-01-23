<?php

namespace App\Livewire\Wedstrijd;

use Livewire\Component;
use Livewire\Attributes\On;

class GroupTableItem extends Component
{
    public $registration;
    public $baan;
    public $group;

    public function mount($registration)
    {
        $this->registration = $registration;
        $this->baan = intdiv($registration->group_id, 10) + 1;
        $this->group = $registration->group_id % 10;
    }

    public function toggle_signoff()
    {
        // Toggle the signed_off value
        $this->registration->update([
            'signed_off' => !$this->registration->signed_off,
        ]);
        $this->dispatch('registration' . $this->registration->id . 'Updated');
        // Render again
        $this->render();
    }

    public function updated($baan, $group)
    {
        $group_id = ($this->baan - 1) * 10 + $this->group;
        $this->registration->update([
            'group_id' => $group_id,
        ]);
    }

    #[On('registration{registration.id}Updated')]
    public function render()
    {
        return view('livewire.wedstrijd.group-table-item');
    }
}
