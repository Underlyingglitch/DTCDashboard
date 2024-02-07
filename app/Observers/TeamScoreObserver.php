<?php

namespace App\Observers;

use App\Models\TeamScore;

class TeamScoreObserver
{
    public function created(TeamScore $score)
    {
        event(new \App\Events\Scores\TeamScoresUpdated($score->match_day_id));
    }

    public function updated(TeamScore $score)
    {
        event(new \App\Events\Scores\TeamScoresUpdated($score->match_day_id));
    }
}
