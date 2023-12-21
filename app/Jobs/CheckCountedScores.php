<?php

namespace App\Jobs;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CheckCountedScores implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Team $team, protected $toestel)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scores = $this->team->registrations->pluck('scores')->flatten()->where('toestel', $this->toestel);
        $thirdHighestScore = $scores->sortByDesc('total')->values()->get(2);

        foreach ($scores as $score) {
            Log::info($score->id . ' - Score: ' . $score->total);
            // Set counted to true if score is among the 3 highest scores for this toestel, otherwise set to false
            if ($thirdHighestScore) {
                $score->counted = $score->total >= $thirdHighestScore->total;
            } else {
                // If there are fewer than 3 scores, all scores are counted
                $score->counted = true;
            }
            $score->save();
        }
    }
}
