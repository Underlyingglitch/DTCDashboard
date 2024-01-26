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
        $this->class = $this->status == 'true' ? 'btn-danger' : 'btn-success';
        $this->label = $this->status == 'true' ? 'Uitschakelen' : 'Inschakelen';
    }

    public function toggle()
    {
        $this->status = $this->status == 'true' ? 'false' : 'true';
        Setting::setValue('sync_enabled', $this->status);
        $this->class = $this->status == 'true' ? 'btn-danger' : 'btn-success';
        $this->label = $this->status == 'true' ? 'Uitschakelen' : 'Inschakelen';
        event(new \App\Events\DataSync\UpdateSyncStatus($this->status == 'true' ? (SyncTask::where('synced', 0)->count() > 0 ? 1 : 3) : 0));
    }

    public function render()
    {
        return view('livewire.sync-toggle-button');
    }
}
