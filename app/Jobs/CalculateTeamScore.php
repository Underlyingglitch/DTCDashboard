<?php

namespace App\Jobs;

use App\Models\Team;
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
    public function __construct(protected Team $team, protected $toestel, protected $match_day_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scores = $this->team->registrations->pluck('scores')->flatten()->where('match_day_id', $this->match_day_id)->where('toestel', $this->toestel);
        $thirdHighestScore = $scores->sortByDesc('total')->values()->get(2);
        foreach ($scores as $score) {
            // Set counted to true if score is among the 3 highest scores for this toestel, otherwise set to false
            if ($thirdHighestScore) {
                $score->counted = $score->total >= $thirdHighestScore->total;
            } else {
                // If there are fewer than 3 scores, all scores are counted
                $score->counted = true;
            }
            $score->save();
        }
        // $toestel = $this->score->toestel;
        // $team = $this->score->registration->team;
        // Sum all counted scores for this team on this toestel
        $team_total_toestel = $scores->where('counted', true)->sum('total');
        // Get the team score for this match day or create it if it doesn't exist
        $team_score = $this->team->team_scores()->firstOrCreate(['match_day_id' => $this->match_day_id]);
        $toestel_scores = $team_score->toestel_scores;
        $toestel_scores[$this->toestel - 1] = $team_total_toestel;
        $team_score->toestel_scores = $toestel_scores;
        $team_score->total_score = array_sum($toestel_scores);
        $team_score->save();
    }
}
