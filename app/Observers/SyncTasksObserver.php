<?php

namespace App\Observers;

use App\Models\SyncTask;

class SyncTasksObserver
{
    public function created(SyncTask $st): void
    {
        // Check if score belongs to a team
        event(new \App\Events\DataSync\UpdateSyncStatus(1));
    }
}
