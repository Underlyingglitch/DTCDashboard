<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use App\Models\SyncTask;

class SyncToggleButton extends Component
{
    public $class;
    public $label;
    public $status;

    public function mount()
    {
        $this->status = Setting::getValue('sync_enabled');
        $this->class = $this->status ? 'btn-danger' : 'btn-success';
        $this->label = $this->status ? 'Uitschakelen' : 'Inschakelen';
    }

    public function toggle()
    {
        $this->status = !$this->status;
        Setting::setValue('sync_enabled', $this->status);
        $this->class = $this->status ? 'btn-danger' : 'btn-success';
        $this->label = $this->status ? 'Uitschakelen' : 'Inschakelen';
        if (env('DO_BROADCASTING', true)) event(new \App\Events\DataSync\UpdateSyncStatus($this->status ? (SyncTask::where('synced', 0)->count() > 0 ? 1 : 3) : 0));
    }

    public function render()
    {
        return view('livewire.sync-toggle-button');
    }
}
