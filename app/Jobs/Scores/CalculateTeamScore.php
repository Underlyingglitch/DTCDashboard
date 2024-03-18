<?php

namespace App\Jobs\Scores;

use App\Models\Team;
use App\Models\Score;
use App\Models\TeamScore;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CalculateTeamScore implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Team $team, protected $toestel, protected $match_day_id)
    {
        //
    }

    public function uniqueId()
    {
        return $this->team->id . '-' . $this->match_day_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scores = $this->team->registrations->where('match_day_id', $this->match_day_id)->pluck('scores')->flatten()->where('match_day_id', $this->match_day_id)->where('toestel', $this->toestel);
        $topScores = $scores->sortByDesc('total')->take($this->team->counting);
        foreach ($scores as $score) {
            // Set counted to true if score is among the 3 highest scores for this toestel, otherwise set to false
            $score->counted = $topScores->contains($score);
            $score->save();
        }
        // Sum all counted scores for this team on this toestel
        $team_total_toestel = $scores->where('counted', true)->sum('total');
        // Get the team score for this match day or create it if it doesn't exist
        $team_score = $this->team->team_scores()->firstOrCreate(['match_day_id' => $this->match_day_id]);
        $toestel_scores = $team_score->toestel_scores;
        $toestel_scores[$this->toestel - 1] = $team_total_toestel;
        $team_score->toestel_scores = $toestel_scores;
        $team_score->total_score = array_sum($toestel_scores);
        $team_score->save();

        // Get the other teams in this match_day and this niveau
        $other_teams = Team::where('niveau_id', $this->team->niveau_id)->whereHas('registrations', function ($query) {
            $query->where('match_day_id', $this->match_day_id);
        })->pluck('id');

        // Sort the teams by total score
        $sorted_team_scores = TeamScore::where('match_day_id', $this->match_day_id)->whereIn('team_id', $other_teams)->get()->sortByDesc('total_score');

        $place = 0;
        $previous = null;
        $same = 1;
        foreach ($sorted_team_scores as $team_score) {
            if ($team_score->total_score != $previous) {
                $previous = $team_score->total_score;
                $place += $same;
                $same = 1;
            } else {
                $same++;
            }
            $team_score->place = $place;
            $team_score->save();
        }
    }
}
