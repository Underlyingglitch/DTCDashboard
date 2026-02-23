<?php

namespace App\Jobs\Scores;

use App\Models\Score;
use App\Models\Registration;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateToestelRanking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $match_day_id, public int $niveau_id, public int $toestel) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startnumbers = Registration::where('match_day_id', $this->match_day_id)
            ->where('niveau_id', $this->niveau_id)
            ->where('signed_off', false)
            ->pluck('startnumber');

        $scores = Score::where('match_day_id', $this->match_day_id)
            ->where('toestel', $this->toestel)
            ->whereIn('startnumber', $startnumbers)
            ->orderBy('total', 'desc')
            ->orderBy('e', 'desc')
            ->get();

        $previousScore = null;
        $place = 0;
        $same = 1;
        foreach ($scores as $score) {
            if ($previousScore !== $score->total) {
                $place += $same;
                $same = 1;
            } else {
                $same++;
            }
            $score->place = $place;
            $score->saveQuietly();
            $previousScore = $score->total;
        }
    }
}
