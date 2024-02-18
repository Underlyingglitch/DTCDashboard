<?php

namespace App\Observers;

use App\Models\Setting;
use App\Models\SyncTask;
use Illuminate\Support\Facades\Log;

class SyncTasksObserver
{
    public function created(SyncTask $st): void
    {
        Log::info('SyncTask created: ' . $st->id);
        if (Setting::getValue('sync_enabled') == 'false') return;
        Log::info('Dispatching UpdateSyncStatus event');
        event(new \App\Events\DataSync\UpdateSyncStatus(1));
    }
}
