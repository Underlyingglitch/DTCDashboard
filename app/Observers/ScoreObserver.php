<?php

namespace App\Observers;

use App\Models\Score;
use App\Jobs\Scores\CalculateTeamScore;
use App\Jobs\Scores\CalculateToestelRanking;

class ScoreObserver
{
    /**
     * Handle the Score "created" event.
     */
    public function created(Score $score): void
    {
        CalculateToestelRanking::dispatch($score->match_day_id, $score->registration->niveau_id, $score->toestel);
        // Check if score belongs to a team
        if ($score->registration->team ?? null) {
            // Check which scores count for this team
            CalculateTeamScore::dispatch($score->match_day_id, $score->registration->team->id, $score->toestel);
        }
        if (env('DO_BROADCASTING', true)) event(new \App\Events\Scores\ScoreUpdated($score->match_day_id, $score));
        \App\Jobs\Scores\UpdateProcessedScore::dispatch($score);
    }

    /**
     * Handle the Score "updated" event.
     */
    public function updated(Score $score): void
    {
        // Only if the total of the score is updated, recalculate the place of the score.
        if ($score->isDirty('total')) {
            CalculateToestelRanking::dispatch($score->match_day_id, $score->registration->niveau_id, $score->toestel);
            if ($score->registration->team ?? null) {
                CalculateTeamScore::dispatch($score->match_day_id, $score->registration->team->id, $score->toestel);
            }
        }
        if (env('DO_BROADCASTING', true)) event(new \App\Events\Scores\ScoreUpdated($score->match_day_id, $score));
    }

    /**
     * Handle the Score "deleting" event.
     */
    public function deleting(Score $score): void
    {
        CalculateToestelRanking::dispatch($score->match_day_id, $score->registration->niveau_id, $score->toestel);
        \App\Jobs\Scores\UpdateProcessedScore::dispatch($score);
    }

    /**
     * Handle the Score "deleted" event.
     */
    public function deleted(Score $score): void
    {
        if ($score->registration->team ?? null) {
            CalculateTeamScore::dispatch($score->match_day_id, $score->registration->team->id, $score->toestel);
        }
    }
}
