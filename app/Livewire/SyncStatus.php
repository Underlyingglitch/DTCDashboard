<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class SyncStatus extends Component
{
    public $status = 0;
    // 0 = Disabled
    // 1 = Enabled, has items to sync
    // 2 = Enabled, syncing
    // 3 = Enabled, no items to sync
    // 4 = Enabled, error syncing
    public $message = null;

    protected $listeners = ['echo:sync_data,.UpdateSyncStatus' => 'updateStatus'];

    public function mount()
    {
        $this->status = Cache::get('sync_status', 0);
    }

    public function sync()
    {
        if ($this->status != 1 && $this->status != 4) return;
        $this->status = 2;
        \App\Jobs\SyncDatabase::dispatch();
    }

    public function updateStatus($input)
    {
        $this->status = $input[0];
        $this->message = $input[1];
    }

    public function render()
    {
        return view('livewire.sync-status');
    }
}
