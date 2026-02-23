<?php

namespace App\Jobs\Scores;

use App\Models\Team;
use App\Models\Score;
use App\Models\TeamScore;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateTeamScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;


    private $team_toestel_scores;
    private Team $team;
    /**
     * Create a new job instance.
     */
    public function __construct(public int $match_day_id, public int $team_id, public int $toestel)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->team = Team::find($this->team_id);

        if (!$this->team) {
            Log::warning("Team {$this->team_id} not found");
            return;
        }
        // Get the team score for this match day or create it if it doesn't exist
        $team_score = $this->team->team_scores()->firstOrCreate(['match_day_id' => $this->match_day_id]);

        $this->team_toestel_scores = (array)$team_score->toestel_scores;

        if ($this->toestel > 0) {
            $this->calculateToestelScore($this->toestel);
        } else {
            for ($toestel = 1; $toestel <= 6; $toestel++) {
                $this->calculateToestelScore($toestel);
            }
        }

        $team_score->toestel_scores = $this->team_toestel_scores;
        $team_score->total_score = array_sum($this->team_toestel_scores);
        $team_score->save();

        $this->updateTeamRanking();
    }


    private function calculateToestelScore($toestel)
    {
        // Get registration startnumbers (not IDs) for this team's match day in one query
        $startnumbers = DB::table('registrations')
            ->where('team_id', $this->team->id)
            ->where('match_day_id', $this->match_day_id)
            ->pluck('startnumber');

        if ($startnumbers->isEmpty()) {
            $this->team_toestel_scores[$toestel - 1] = 0;
            return;
        }

        // Get all scores for this toestel in one query, ordered by total DESC
        $scores = Score::whereIn('startnumber', $startnumbers)
            ->where('match_day_id', $this->match_day_id)
            ->where('toestel', $toestel)
            ->whereNotNull('d')
            ->orderByDesc('total')
            ->get();

        if ($scores->isEmpty()) {
            $this->team_toestel_scores[$toestel - 1] = 0;
            return;
        }

        // Get top N scores based on team counting rules
        $topScores = $scores->take($this->team->counting);
        $topScoreIds = $topScores->pluck('id')->toArray();

        // Bulk update: set counted = true for top scores
        if (!empty($topScoreIds)) {
            Score::whereIn('id', $topScoreIds)->update(['counted' => true]);
        }

        // Bulk update: set counted = false for remaining scores
        $remainingIds = $scores->pluck('id')->diff($topScoreIds)->toArray();
        if (!empty($remainingIds)) {
            Score::whereIn('id', $remainingIds)->update(['counted' => false]);
        }

        // Calculate team total for this toestel
        $this->team_toestel_scores[$toestel - 1] = $topScores->sum('total');
    }

    private function updateTeamRanking()
    {
        // Get other teams in this niveau with registrations for this match day
        $teamIds = DB::table('teams')
            ->where('niveau_id', $this->team->niveau_id)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('registrations')
                    ->whereColumn('registrations.team_id', 'teams.id')
                    ->where('registrations.match_day_id', $this->match_day_id);
            })
            ->pluck('id');

        // Get and sort team scores
        $sorted_team_scores = TeamScore::where('match_day_id', $this->match_day_id)
            ->whereIn('team_id', $teamIds)
            ->orderByDesc('total_score')
            ->get();

        // Bulk update places
        $updates = [];
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
            $updates[] = [
                'id' => $team_score->id,
                'place' => $place
            ];
        }

        // Batch update using case statement for better performance
        if (!empty($updates)) {
            $cases = [];
            $ids = [];
            foreach ($updates as $update) {
                $cases[] = "WHEN {$update['id']} THEN {$update['place']}";
                $ids[] = $update['id'];
            }

            DB::table('team_scores')
                ->whereIn('id', $ids)
                ->update([
                    'place' => DB::raw('CASE id ' . implode(' ', $cases) . ' END')
                ]);
        }
    }
}
