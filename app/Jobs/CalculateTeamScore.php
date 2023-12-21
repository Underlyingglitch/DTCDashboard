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

class CalculateTeamScore implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 10;

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
        $team_total_toestel = $team->registrations->pluck('scores')->flatten()->where('match_day_id', $this->score->match_day_id)->where('toestel', $toestel)->where('counted', true)->sum('total');
        // Get the team score for this match day or create it if it doesn't exist
        $team_score = $team->team_scores()->firstOrCreate(['match_day_id' => $this->score->match_day_id]);
        $toestel_scores = $team_score->toestel_scores;
        $toestel_scores[$toestel - 1] = $team_total_toestel;
        $team_score->toestel_scores = $toestel_scores;
        $team_score->total_score = array_sum($toestel_scores);
        $team_score->save();
    }

    public function uniqueId(): string
    {
        return $this->score->registration->team;
    }
}
