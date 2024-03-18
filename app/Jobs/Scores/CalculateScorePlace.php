<?php

namespace App\Jobs\Scores;

use App\Models\Score;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateScorePlace implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Score $score)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startnumbers = Registration::where('match_day_id', $this->score->match_day_id)
            ->where('niveau_id', $this->score->registration->niveau_id)
            ->where('signed_off', false)
            ->pluck('startnumber');

        $scores = Score::where('match_day_id', $this->score->match_day_id)
            ->where('toestel', $this->score->toestel)
            ->whereIn('startnumber', $startnumbers)
            ->orderBy('total', 'desc')
            ->orderBy('e', 'desc')
            ->get();
        foreach ($scores as $key => $score) $score->update([
            'place' => $score->total == 0 ? null : $key + 1,
        ]);
    }
}
