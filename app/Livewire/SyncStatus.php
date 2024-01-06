<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class SyncStatus extends Component
{
    public $status = 0;

    protected $listeners = ['echo:sync_data,.SyncStarted' => 'sync', 'echo:sync_data,.SyncFinished' => 'sync', 'echo:sync_data,.SyncFailed' => 'sync'];

    public function mount()
    {
        $this->status = Cache::get('sync_status', 0);
    }

    public function sync($input)
    {
        Cache::put('sync_status', $input[0]);
        $this->status = $input[0];
    }

    public function render()
    {
        return view('livewire.sync-status');
    }
}
