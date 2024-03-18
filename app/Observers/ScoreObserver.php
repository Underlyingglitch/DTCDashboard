<?php

namespace App\Observers;

use App\Models\Score;
use App\Jobs\Scores\CalculateTeamScore;
use App\Jobs\Scores\CalculateScorePlace;
use App\Jobs\CheckCountedScores;
use Illuminate\Support\Facades\Log;

class ScoreObserver
{
    /**
     * Handle the Score "created" event.
     */
    public function created(Score $score): void
    {
        CalculateScorePlace::dispatch($score);
        // Check if score belongs to a team
        if ($score->registration->team ?? null) {
            // Check which scores count for this team
            CalculateTeamScore::dispatch($score->registration->team, $score->toestel, $score->match_day_id);
        }
        event(new \App\Events\Scores\ScoreUpdated($score->match_day_id, $score));
        \App\Jobs\Scores\UpdateProcessedScore::dispatch($score);
    }

    /**
     * Handle the Score "updated" event.
     */
    public function updated(Score $score): void
    {
        // If only the place of the score is updated, we don't need to recalculate the place of all scores.
        if ($score->isDirty('total')) {
            CalculateScorePlace::dispatch($score);
        }
        if ($score->registration->team ?? null) {
            if ($score->isDirty('total')) {
                CalculateTeamScore::dispatch($score->registration->team, $score->toestel, $score->match_day_id);
            }
        }
        event(new \App\Events\Scores\ScoreUpdated($score->match_day_id, $score));
    }

    /**
     * Handle the Score "deleting" event.
     */
    public function deleting(Score $score): void
    {
        CalculateScorePlace::dispatch($score);
        \App\Jobs\Scores\UpdateProcessedScore::dispatch($score);
    }

    /**
     * Handle the Score "deleted" event.
     */
    public function deleted(Score $score): void
    {
        if ($score->registration->team ?? null) {
            CalculateTeamScore::dispatch($score->registration->team, $score->toestel, $score->match_day_id);
        }
    }
}
