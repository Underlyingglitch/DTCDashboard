<?php

namespace App\Observers;

use App\Models\Setting;
use App\Models\SyncTask;

class SyncTasksObserver
{
    public function created(SyncTask $st): void
    {
        if (Setting::getValue('sync_enabled') == 'false') return;
        event(new \App\Events\DataSync\UpdateSyncStatus(1));
    }
}
