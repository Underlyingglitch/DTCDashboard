<?php

namespace App\Jobs\Scores;

use App\Models\Score;
use App\Models\Wedstrijd;
use Illuminate\Bus\Queueable;
use App\Models\ProcessedScore;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateProcessedScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $score;
    public $score_registration;

    /**
     * Create a new job instance.
     */
    public function __construct(Score $score)
    {
        $this->score_registration = $score->registration;
        $this->score = $score->toArray();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $score_registration = $this->score_registration;
        // Get the wedstrijd for this score from the niveau on the registration
        $niveau = $score_registration->niveau;
        $wedstrijd = Wedstrijd::where('match_day_id', $this->score['match_day_id'])->whereHas('niveaus', function ($query) use ($niveau) {
            $query->where('niveaus.id', $niveau->id);
        })->first();
        $startnumbers = $wedstrijd->registrations->where('group_id', $score_registration->group_id)->where('signed_off', false)->pluck('startnumber');
        $scores = Score::where([
            ['toestel', $this->score['toestel']],
            ['match_day_id', $this->score['match_day_id']]
        ])->whereIn('startnumber', $startnumbers)->count();
        if ($scores == 0) {
            $ps = ProcessedScore::where([
                'group_id' => $score_registration->group_id,
                'toestel' => $this->score['toestel'],
                'wedstrijd_id' => $wedstrijd->id,
            ])->first();
            if (env('DO_BROADCASTING', true)) event(new \App\Events\ProcessedScoreUpdated($ps, true));
            $ps->delete();
            return;
        }
        ProcessedScore::updateOrCreate([
            'group_id' => $score_registration->group_id,
            'toestel' => $this->score['toestel'],
            'wedstrijd_id' => $wedstrijd->id,
        ], [
            'completed' => count($startnumbers) == $scores
        ]);
    }
}
