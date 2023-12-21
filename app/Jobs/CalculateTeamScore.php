<?php

namespace App\Jobs;

use App\Models\Score;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CalculateTeamScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Score $score)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $toestel = $this->score->toestel;
        $team = $this->score->registration->team;
        // Sum all counted scores for this team on this toestel
        $teamScore = $team->registrations->pluck('scores')->flatten()->where('toestel', $toestel)->where('counted', true)->sum('total');
        $toestel_scores = $team->toestel_scores;
        $toestel_scores[$toestel - 1] = $teamScore;
        $team->toestel_scores = $toestel_scores;
        $team->total_score = array_sum($toestel_scores);
        $team->save();
    }
}
