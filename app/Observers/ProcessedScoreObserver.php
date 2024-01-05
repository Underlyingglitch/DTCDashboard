<?php

namespace App\Observers;

use App\Models\ProcessedScore;
use App\Events\ProcessedScoreUpdated;

class ProcessedScoreObserver
{
    /**
     * Handle the Score "created" event.
     */
    public function created(ProcessedScore $ps): void
    {
        event(new ProcessedScoreUpdated($ps));
    }

    /**
     * Handle the Score "updated" event.
     */
    public function updated(ProcessedScore $ps): void
    {
        event(new ProcessedScoreUpdated($ps));
    }

    /**
     * Handle the Score "deleted" event.
     */
    public function deleted(ProcessedScore $ps): void
    {
        event(new ProcessedScoreUpdated($ps));
    }

    /**
     * Handle the Score "restored" event.
     */
    public function restored(ProcessedScore $ps): void
    {
        event(new ProcessedScoreUpdated($ps));
    }

    /**
     * Handle the Score "force deleted" event.
     */
    public function forceDeleted(ProcessedScore $ps): void
    {
        event(new ProcessedScoreUpdated($ps));
    }
}
