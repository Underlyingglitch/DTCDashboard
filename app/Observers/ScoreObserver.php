<?php

namespace App\Observers;

use App\Models\Score;
use App\Jobs\CalculateTeamScore;
use App\Jobs\CheckCountedScores;
use Illuminate\Support\Facades\Log;

class ScoreObserver
{
    /**
     * Handle the Score "created" event.
     */
    public function created(Score $score): void
    {
        Log::info('Score created');
        // Check if score belongs to a team
        if ($score->registration->team) {
            // Check which scores count for this team
            CheckCountedScores::dispatch($score);
        }
    }

    /**
     * Handle the Score "updated" event.
     */
    public function updated(Score $score): void
    {
        Log::info('Score updated');
        // Check if the counted column is changed
        if ($score->isDirty('counted')) {
            Log::info('Counted column changed');
            CalculateTeamScore::dispatch($score);
        } else {
            // Check if score belongs to a team
            if ($score->registration->team) {
                // Check which scores count for this team
                CheckCountedScores::dispatch($score);
            }
        }
    }

    /**
     * Handle the Score "deleted" event.
     */
    public function deleted(Score $score): void
    {
        CheckCountedScores::dispatch($score);
        CalculateTeamScore::dispatch($score);
    }

    /**
     * Handle the Score "restored" event.
     */
    public function restored(Score $score): void
    {
        CheckCountedScores::dispatch($score);
        CalculateTeamScore::dispatch($score);
    }

    /**
     * Handle the Score "force deleted" event.
     */
    public function forceDeleted(Score $score): void
    {
        CheckCountedScores::dispatch($score);
        CalculateTeamScore::dispatch($score);
    }
}
